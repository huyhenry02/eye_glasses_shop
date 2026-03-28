<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function showIndex(Request $request): RedirectResponse|View
    {
        try {
            $keyword = trim((string) $request->keyword);
            $status = trim((string) $request->status);
            $paymentStatus = trim((string) $request->payment_status);
            $paymentMethod = trim((string) $request->payment_method);

            $orders = Order::query()
                ->with(['customer', 'employee'])
                ->withCount('orderDetails')
                ->when($keyword !== '', function ($query) use ($keyword) {
                    $query->where(function ($q) use ($keyword) {
                        $q->where('order_code', 'like', '%' . $keyword . '%')
                            ->orWhere('shipping_name', 'like', '%' . $keyword . '%')
                            ->orWhere('shipping_phone', 'like', '%' . $keyword . '%')
                            ->orWhere('shipping_email', 'like', '%' . $keyword . '%');
                    });
                })
                ->when($status !== '', fn($query) => $query->where('status', $status))
                ->when($paymentStatus !== '', fn($query) => $query->where('payment_status', $paymentStatus))
                ->when($paymentMethod !== '', fn($query) => $query->where('payment_method', $paymentMethod))
                ->latest('id')
                ->paginate(10)
                ->withQueryString();

            return view('admin.pages.order.index', compact('orders'));
        } catch (\Throwable $e) {
            return back()->with('error', 'Không thể tải danh sách đơn hàng.');
        }
    }

    public function showDetail(int $id): RedirectResponse|View
    {
        try {
            $order = Order::query()
                ->with(['customer', 'employee', 'orderDetails.product'])
                ->findOrFail($id);

            return view('admin.pages.order.detail', compact('order'));
        } catch (\Throwable $e) {
            return redirect()->route('admin.order.showIndex')->with('error', 'Không tìm thấy đơn hàng.');
        }
    }

    public function showEdit(int $id): RedirectResponse|View
    {
        try {
            $order = Order::query()
                ->with(['customer', 'employee', 'orderDetails.product'])
                ->findOrFail($id);

            return view('admin.pages.order.edit', compact('order'));
        } catch (\Throwable $e) {
            return redirect()->route('admin.order.showIndex')->with('error', 'Không tìm thấy đơn hàng.');
        }
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        try {
            $order = Order::query()->findOrFail($id);

            $data = $request->validate([
                'shipping_name' => ['required', 'string', 'max:255'],
                'shipping_phone' => ['required', 'string', 'max:20'],
                'shipping_email' => ['nullable', 'email', 'max:255'],
                'shipping_address' => ['required', 'string'],
                'status' => ['required', Rule::in(array_keys(Order::STATUSES))],
                'payment_status' => ['required', Rule::in(array_keys(Order::PAYMENT_STATUSES))],
                'payment_method' => ['required', Rule::in(array_keys(Order::PAYMENT_METHODS))],
            ]);

            if ($data['status'] === Order::STATUS_COMPLETED && empty($order->completed_at)) {
                $data['completed_at'] = now()->toDateString();
            }

            if ($data['status'] !== Order::STATUS_COMPLETED) {
                $data['completed_at'] = null;
            }

            if ($data['payment_status'] === Order::PAYMENT_STATUS_PAID && empty($order->payment_time)) {
                $data['payment_time'] = now();
            }

            if ($data['payment_status'] !== Order::PAYMENT_STATUS_PAID && $order->payment_method === Order::PAYMENT_METHOD_COD) {
                $data['payment_time'] = null;
            }

            $data['updated_by'] = session('employee_id');

            $order->update($data);

            return redirect()->route('admin.order.showIndex')->with('success', 'Cập nhật đơn hàng thành công.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Cập nhật đơn hàng thất bại.');
        }
    }

    public function destroy(int $id): RedirectResponse
    {
        try {
            $order = Order::query()->findOrFail($id);
            $order->delete();

            return redirect()->route('admin.order.showIndex')->with('success', 'Xóa đơn hàng thành công.');
        } catch (\Throwable $e) {
            return redirect()->route('admin.order.showIndex')->with('error', 'Xóa đơn hàng thất bại.');
        }
    }
}
