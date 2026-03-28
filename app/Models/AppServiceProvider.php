<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('customer.layouts.header', function ($view) {
            $cartCountGlobal = 0;

            if (Auth::check() && Auth::user()->user_type === User::ROLE_CUSTOMER) {
                $customer = Customer::where('user_id', Auth::id())->first();

                if ($customer) {
                    $cartCountGlobal = (int) Cart::where('customer_id', $customer->id)->sum('quantity');
                }
            }

            $view->with('cartCountGlobal', $cartCountGlobal);
        });
    }
}
