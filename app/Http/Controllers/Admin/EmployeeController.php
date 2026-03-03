<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Throwable;

class EmployeeController extends Controller
{
    public function showIndex()
    {
        $employees = Employee::all();
        return view('admin.pages.employee.index', [
            'employees' => $employees
        ]);
    }

    public function showCreate()
    {
        return view('admin.pages.employee.edit', [
            'mode' => 'create',
            'employee' => null,
        ]);
    }

    public function showEdit($id)
    {
        $employee = Employee::with('user')->findOrFail($id);
        return view('admin.pages.employee.edit', [
            'mode' => 'edit',
            'employee' => $employee,
        ]);
    }

    public function store(Request $request): ?RedirectResponse
    {
        try {
            $data = $request->input();
            DB::transaction(static function () use ($data) {
                $user = User::create([
                    'phone' => $data['phone'],
                    'password' => Hash::make($data['password']),
                    'user_type' => User::ROLE_EMPLOYEE,
                ]);

                Employee::create([
                    'user_id' => $user->id,
                    'full_name' => $data['full_name'],
                    'email' => $data['email'],
                    'position' => $data['position'],
                    'address' => $data['address'],
                ]);
            });

            return redirect()->route('admin.employee.showIndex')->with('success', 'Thêm nhân viên thành công.');
        } catch (ValidationException $e) {
            throw $e;
        } catch (Throwable $e) {
            return back()->withInput()->with('error', 'Thêm nhân viên thất bại.');
        }
    }

    public function update(Request $request, $id): ?RedirectResponse
    {
        try {
            $employee = Employee::with('user')->findOrFail($id);
            $data = $request->input();
            DB::transaction(function () use ($employee, $data) {
                $employee->user->update([
                    'phone' => $data['phone'],
                    'user_type' => User::ROLE_EMPLOYEE,
                    ...(!empty($data['password']) ? ['password' => Hash::make($data['password'])] : []),
                ]);

                $employee->update([
                    'full_name' => $data['full_name'],
                    'email' => $data['email'],
                    'position' => $data['position'],
                    'address' => $data['address'],
                ]);
            });

            return redirect()->route('admin.employee.showIndex')->with('success', 'Cập nhật nhân viên thành công.');
        } catch (ValidationException $e) {
            throw $e;
        } catch (Throwable $e) {
            return back()->withInput()->with('error', 'Cập nhật nhân viên thất bại.');
        }
    }

    public function destroy($id): ?RedirectResponse
    {
        try {
            $employee = Employee::findOrFail($id);

            DB::transaction(function () use ($employee) {
                User::where('id', $employee->user_id)->delete();
            });

            return redirect()->route('admin.employee.showIndex')->with('success', 'Xóa nhân viên thành công.');
        } catch (Throwable $e) {
            return redirect()->route('admin.employee.showIndex')->with('error', 'Xóa nhân viên thất bại.');
        }
    }
}
