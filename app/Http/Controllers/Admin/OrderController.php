<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function showIndex(Request $request)
    {
        try {
            $keyword = trim((string) $request->keyword);
            $status = trim((string) $request->status);
            $paymentStatus = trim((string) $request->payment_status);
            $paymentMethod = trim((string) $request->payment_method);

            $orders = Order::with(['customer', 'employee'])
                ->when($keyword !== '', function ($query) use ($keyword) {
                    $query->where(function ($q) use ($keyword) {
                        $q->where('order_code', 'like', '%' . $keyword . '%')
                            ->orWhere('shipping_name', 'like', '%' . $keyword . '%')
                            ->orWhere('shipping_phone', 'like', '%' . $keyword . '%')
                            ->orWhere('shipping_email', 'like', '%' . $keyword . '%');
                    });
                })
                ->when($status !== '', fn($q) => $q->where('status', $status))
                ->when($paymentStatus !== '', fn($q) => $q->where('payment_status', $paymentStatus))
                ->when($paymentMethod !== '', fn($q) => $q->where('payment_method', $paymentMethod))
                ->latest('id')
                ->paginate(10)
                ->withQueryString();

            return view('admin.pages.order.index', compact('orders'));
        } catch (\Throwable $e) {
            return back()->with('error', 'Không thể tải danh sách đơn hàng.');
        }
    }

    public function showDetail($id)
    {
        try {
            $order = Order::with(['customer', 'employee', 'orderDetails'])->findOrFail($id);

            return view('admin.pages.order.detail', compact('order'));
        } catch (\Throwable $e) {
            return redirect()->route('admin.order.showIndex')->with('error', 'Không tìm thấy đơn hàng.');
        }
    }

    public function showEdit($id)
    {
        try {
            $order = Order::with(['customer', 'employee', 'orderDetails'])->findOrFail($id);

            return view('admin.pages.order.edit', compact('order'));
        } catch (\Throwable $e) {
            return redirect()->route('admin.order.showIndex')->with('error', 'Không tìm thấy đơn hàng.');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $order = Order::findOrFail($id);

            $data = $request->validate([
                'shipping_name' => ['required', 'string'],
                'shipping_phone' => ['required', 'string'],
                'shipping_email' => ['nullable', 'email'],
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

            $data['updated_by'] = session('employee_id');

            $order->update($data);

            return redirect()->route('admin.order.showIndex')->with('success', 'Cập nhật đơn hàng thành công.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Cập nhật đơn hàng thất bại.');
        }
    }

    public function destroy($id)
    {
        try {
            $order = Order::findOrFail($id);
            $order->delete();

            return redirect()->route('admin.order.showIndex')->with('success', 'Xóa đơn hàng thành công.');
        } catch (\Throwable $e) {
            return redirect()->route('admin.order.showIndex')->with('error', 'Xóa đơn hàng thất bại.');
        }
    }
}
