<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    public function showIndex()
    {
        $categories = Category::all();
        return view('admin.pages.category.index', [
            'categories' => $categories
        ]);
    }
    public function showCreate()
    {
        return view('admin.pages.category.edit', [
            'mode' => 'create',
            'category' => null,
        ]);
    }

    public function showEdit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.pages.category.edit', [
            'mode' => 'edit',
            'category' => $category,
        ]);
    }

    public function store(Request $request): ?RedirectResponse
    {
        try {
            $data = $request->input();
            $data['sizes'] = $this->normalizeSizes($data['sizes'] ?? null);
            Category::create($data);
            return redirect()->route('admin.category.showIndex')->with('success', 'Tạo danh mục thành công.');
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Tạo danh mục thất bại.');
        }
    }

    public function update(Request $request, $id): ?RedirectResponse
    {
        try {
            $category = Category::findOrFail($id);
            $data = $request->input();
            $data['sizes'] = $this->normalizeSizes($data['sizes'] ?? null);
            $category->update($data);
            return redirect()->route('admin.category.showIndex')->with('success', 'Cập nhật danh mục thành công.');
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Cập nhật danh mục thất bại.');
        }
    }

    public function destroy($id): ?RedirectResponse
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();
            return redirect()->route('admin.category.showIndex')->with('success', 'Xóa danh mục thành công.');
        } catch (\Throwable $e) {
            return redirect()->route('admin.category.showCreate')->with('error', 'Xóa danh mục thất bại.');
        }
    }

    private function normalizeSizes(?string $sizes): ?string
    {
        $sizes = trim((string) $sizes);
        if ($sizes === '') {
            return null;
        }
        $arr = array_filter(array_map(function ($s) {
            $s = strtoupper(trim($s));
            $s = preg_replace('/\s+/', '', $s);
            return $s ?: null;
        }, explode(',', $sizes)));
        $arr = array_values(array_unique($arr));

        return $arr ? implode(',', $arr) : null;
    }
}
