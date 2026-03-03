<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Throwable;

class CustomerController extends Controller
{
    public function showIndex()
    {
        $customers = Customer::all();
        return view('admin.pages.customer.index', [
            'customers' => $customers
        ]);
    }
    public function showCreate()
    {
        return view('admin.pages.customer.edit', [
            'mode' => 'create',
            'customer' => null,
        ]);
    }

    public function showEdit($id)
    {
        $customer = Customer::with('user')->findOrFail($id);
        return view('admin.pages.customer.edit', [
            'mode' => 'edit',
            'customer' => $customer,
        ]);
    }

    public function store(Request $request): ?RedirectResponse
    {
        try {
            $data = $request->input();

            DB::transaction(function () use ($data) {
                $user = User::create([
                    'phone' => $data['phone'],
                    'password' => Hash::make($data['password']),
                    'user_type' => User::ROLE_CUSTOMER,
                ]);

                Customer::create([
                    'user_id' => $user->id,
                    'full_name' => $data['full_name'],
                    'email' => $data['email'],
                    'address' => $data['address'],
                    'gender' => $data['gender'],
                    'birthday' => $data['birthday'],
                ]);
            });

            return redirect()->route('admin.customer.showIndex')->with('success', 'Thêm khách hàng thành công.');
        } catch (ValidationException $e) {
            throw $e;
        } catch (Throwable $e) {
            return back()->withInput()->with('error', 'Thêm khách hàng thất bại.');
        }
    }

    public function update(Request $request, $id): ?RedirectResponse
    {
        try {
            $customer = Customer::with('user')->findOrFail($id);
            $userId = $customer->user_id;

            $data = $request->input();

            DB::transaction(static function () use ($customer, $data) {
                $customer->user->update([
                    'phone' => $data['phone'],
                    'user_type' => User::ROLE_CUSTOMER,
                    ...(!empty($data['password']) ? ['password' => Hash::make($data['password'])] : []),
                ]);

                $customer->update([
                    'full_name' => $data['full_name'],
                    'email' => $data['email'],
                    'address' => $data['address'],
                    'gender' => $data['gender'],
                    'birthday' => $data['birthday'],
                ]);
            });

            return redirect()->route('admin.customer.showIndex')->with('success', 'Cập nhật khách hàng thành công.');
        } catch (ValidationException $e) {
            throw $e;
        } catch (Throwable $e) {
            return back()->withInput()->with('error', 'Cập nhật khách hàng thất bại.');
        }
    }

    public function destroy($id): ?RedirectResponse
    {
        try {
            $customer = Customer::findOrFail($id);

            DB::transaction(function () use ($customer) {
                $userId = $customer->user_id;
                User::where('id', $userId)->delete();
            });

            return redirect()->route('admin.customer.showIndex')->with('success', 'Xóa khách hàng thành công.');
        } catch (Throwable $e) {
            return redirect()->route('admin.customer.showIndex')->with('error', 'Xóa khách hàng thất bại.');
        }
    }

}
