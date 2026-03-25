<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    public function showIndex()
    {
        $categories = Category::orderByDesc('id')->get();

        return view('admin.pages.category.index', [
            'categories' => $categories,
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
            $data = $this->validateCategory($request);

            Category::create($data);

            return redirect()
                ->route('admin.category.showIndex')
                ->with('success', 'Tạo danh mục thành công.');
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->with('error', 'Tạo danh mục thất bại.');
        }
    }

    public function update(Request $request, $id): ?RedirectResponse
    {
        try {
            $category = Category::findOrFail($id);
            $data = $this->validateCategory($request, $category->id);

            $category->update($data);

            return redirect()
                ->route('admin.category.showIndex')
                ->with('success', 'Cập nhật danh mục thành công.');
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->with('error', 'Cập nhật danh mục thất bại.');
        }
    }

    public function destroy($id): ?RedirectResponse
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();

            return redirect()
                ->route('admin.category.showIndex')
                ->with('success', 'Xóa danh mục thành công.');
        } catch (\Throwable $e) {
            return redirect()
                ->route('admin.category.showIndex')
                ->with('error', 'Xóa danh mục thất bại.');
        }
    }

    private function validateCategory(Request $request, ?int $id = null): array
    {
        $data = $request->validate([
            'code' => [
                'required',
                'string',
                'max:100',
                Rule::unique('categories', 'code')->ignore($id),
            ],
            'name' => [
                'required',
                'string',
                'max:150',
            ],
            'slug' => [
                'nullable',
                'string',
                'max:160',
                Rule::unique('categories', 'slug')->ignore($id),
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'is_active' => [
                'nullable',
                'in:0,1',
            ],
        ], [
            'code.required' => 'Vui lòng nhập mã danh mục.',
            'code.unique' => 'Mã danh mục đã tồn tại.',
            'name.required' => 'Vui lòng nhập tên danh mục.',
            'slug.unique' => 'Slug đã tồn tại.',
        ]);

        $data['code'] = trim((string) $data['code']);
        $data['name'] = trim((string) $data['name']);
        $data['description'] = isset($data['description']) ? trim((string) $data['description']) : null;
        $data['slug'] = !empty($data['slug'])
            ? Str::slug(trim((string) $data['slug']))
            : Str::slug($data['name']);
        $data['is_active'] = (int) ($data['is_active'] ?? 1);

        return $data;
    }
}
