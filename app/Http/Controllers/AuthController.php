<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function postLogin(Request $request): RedirectResponse
    {
        try {
            $credentials = $request->only('phone', 'password');
            if (auth()->attempt($credentials)) {
                $user = auth()->user();
                if ($user->user_type === 'customer') {
                    return redirect()->route('customer.showIndex')->with('success', 'Đăng nhập thành công');
                }
                return redirect()->route('admin.dashboard.showIndex')->with('success', 'Đăng nhập thành công');
            }
            return redirect()->back()->with('error', 'Đăng nhập thất bại');
        } catch (Exception $e) {
            return redirect()->route('auth.showLogin')->with('error', 'Đăng nhập thất bại');
        }
    }

    public function logout(): RedirectResponse
    {
        try {
            auth()->logout();
            return redirect()->route('auth.showLogin')->with('success', 'Đăng xuất thành công');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Đăng xuất thất bại');
        }
    }

    public function postRegister(Request $request): RedirectResponse
    {
        try {
            $data = $request->input();

            if (($data['password'] ?? '') !== ($data['password_confirmation'] ?? '')) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Mật khẩu nhập lại không khớp.');
            }

            DB::beginTransaction();

            $user = User::create([
                'password' => Hash::make($data['password']),
                'phone' => $data['phone'],
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

            DB::commit();

            return redirect()->route('auth.showLogin')->with('success', 'Đăng ký thành công.');
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Đăng ký thất bại.');
        }
    }
}
