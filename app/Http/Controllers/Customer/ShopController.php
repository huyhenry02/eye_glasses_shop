<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function showIndex()
    {
        $products = Product::with('category')
            ->where('is_active', 1)
            ->latest()
            ->get();

        $featuredProducts = $products->take(3)->values();

        $highlightProduct = $products->first();

        $newArrivalProducts = $products->skip(1)->take(4)->values();

        $inspiredProducts = $products->skip(5)->take(8)->values();

        return view('customer.pages.index', [
            'featuredProducts' => $featuredProducts,
            'highlightProduct' => $highlightProduct,
            'newArrivalProducts' => $newArrivalProducts,
            'inspiredProducts' => $inspiredProducts,
        ]);
    }

    public function showContact()
    {
        return view('customer.pages.contact');
    }

    public function showBlog()
    {
        return view('customer.pages.blog');
    }

    public function showBlogDetail()
    {
        return view('customer.pages.blog-detail');
    }

    public function showProducts()
    {
        $categories = Category::all();
        $products = Product::all();
        return view('customer.pages.products',
            [
                'categories' => $categories,
                'products' => $products
            ]);
    }

    public function showProductDetail(Product $product)
    {
        $productsFeatured = Product::where('is_featured', 1)->get();
        if (!empty($product['category_id'])) {
            $category = Category::find($product['category_id']);
            if (!empty($category['sizes'])) {
                $product['sizeArray'] = explode(',', $category['sizes']);
            }
        }
        if (!empty($product['colors'])) {
            $product['colorArray'] = explode(',', $product['colors']);
        }
        return view('customer.pages.product-detail',
            [
                'product' => $product,
                'productsFeatured' => $productsFeatured
            ]);
    }

}
