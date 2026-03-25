<?php

namespace App\Models;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer(['customer.layouts.cart', 'customer.layouts.header'], function ($view) {
            $cartItems = collect();
            $cartCount = 0;
            $cartTotal = 0;
            if (auth()->check() && auth()->user()->user_type === 'customer') {
                $userId = auth()->id();
                $customer = Customer::where('user_id', $userId)->first();
                $customerId = $customer->id;

                $cartItems = Cart::with('product')
                    ->where('customer_id', $customerId)
                    ->latest()
                    ->get();

                $cartCount = $cartItems->sum('quantity');

                $cartTotal = $cartItems->sum(function ($item) {
                    $price = (!empty($item->product->discount_price) && (int)$item->product->discount_price > 0)
                        ? (int)$item->product->discount_price
                        : (int)($item->product->price ?? 0);

                    return $price * (int)$item->quantity;
                });
            }

            $view->with([
                'cartItemsGlobal' => $cartItems,
                'cartCountGlobal' => $cartCount,
                'cartTotalGlobal' => $cartTotal,
            ]);
        });
    }
}
