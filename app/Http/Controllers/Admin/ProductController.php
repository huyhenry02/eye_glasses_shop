<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Throwable;

class ProductController extends Controller
{
    public function showIndex()
    {
        $products = Product::with('category')
            ->orderByDesc('id')
            ->get();
        return view('admin.pages.product.index', [
            'products' => $products
        ]);
    }

    public function showCreate()
    {
        $categories = Category::where('is_active', 1)->orderBy('name')->get();
        return view('admin.pages.product.edit', [
            'mode' => 'create',
            'product' => null,
            'categories' => $categories
        ]);
    }

    public function showEdit($id)
    {
        $categories = Category::where('is_active', 1)->orderBy('name')->get();
        $product = Product::findOrFail($id);
        return view('admin.pages.product.edit', [
            'mode' => 'edit',
            'product' => $product,
            'categories' => $categories
        ]);
    }

    public function showDetail($id)
    {
        $product = Product::with('category')->findOrFail($id);
        return view('admin.pages.product.detail', [
            'product' => $product,
        ]);
    }

    public function store(Request $request): ?RedirectResponse
    {
        try {
            $data = $request->input();
            foreach (['image', 'image_detail_1', 'image_detail_2', 'image_detail_3'] as $field) {
                if ($request->hasFile($field)) {
                    $data[$field] = $this->storeImage($request->file($field));
                }
            }
            Product::create($data);
            return redirect()
                ->route('admin.product.showIndex')
                ->with('success', 'Thêm sản phẩm thành công.');
        } catch (ValidationException $e) {
            throw $e;
        } catch (Throwable $e) {
            return back()
                ->withInput()
                ->with('error', 'Thêm sản phẩm thất bại.');
        }
    }

    public function update(Request $request, $id): ?RedirectResponse
    {
        try {
            $product = Product::findOrFail($id);
            $data = $request->input();
            foreach (['image', 'image_detail_1', 'image_detail_2', 'image_detail_3'] as $field) {
                if ($request->hasFile($field)) {
                    $this->deleteImageIfLocal($product->{$field} ?? null);
                    $data[$field] = $this->storeImage($request->file($field));
                }
            }
            $product->update($data);
            return redirect()
                ->route('admin.product.showIndex')
                ->with('success', 'Cập nhật sản phẩm thành công.');
        } catch (ValidationException $e) {
            throw $e;
        } catch (Throwable $e) {
            return back()
                ->withInput()
                ->with('error', 'Cập nhật sản phẩm thất bại.');
        }
    }

    public function destroy($id): ?RedirectResponse
    {
        try {
            $product = Product::findOrFail($id);
            foreach (['image', 'image_detail_1', 'image_detail_2', 'image_detail_3'] as $field) {
                $this->deleteImageIfLocal($product->{$field} ?? null);
            }
            $product->delete();
            return redirect()
                ->route('admin.product.showIndex')
                ->with('success', 'Xóa sản phẩm thành công.');
        } catch (Throwable $e) {
            return redirect()
                ->route('admin.product.showIndex')
                ->with('error', 'Xóa sản phẩm thất bại.');
        }
    }

    private function normalizeSlug(?string $slug, string $name): string
    {
        $slug = trim((string)$slug);
        return $slug !== '' ? Str::slug($slug) : Str::slug($name);
    }

    private function storeImage($file): string
    {
        $ext = strtolower($file->getClientOriginalExtension() ?: 'png');
        $name = Str::random(20) . '.' . $ext;
        $path = $file->storeAs('products', $name, 'public');
        return '/storage/' . $path;
    }

    private function deleteImageIfLocal(?string $url): void
    {
        $url = trim((string)$url);
        if ($url === '') {
            return;
        }
        $path = parse_url($url, PHP_URL_PATH) ?: $url;
        if (str_starts_with($path, '/storage/')) {
            $relative = ltrim(str_replace('/storage/', '', $path), '/');
            Storage::disk('public')->delete($relative);
        }
    }
}
