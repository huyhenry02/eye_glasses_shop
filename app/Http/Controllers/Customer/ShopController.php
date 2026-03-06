<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function showIndex()
    {
        return view('customer.pages.index');
    }
    public function showContact()
    {
        return view('customer.pages.contact');
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
            'product' => $product
        ]);
    }
}
