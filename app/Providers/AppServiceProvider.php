<?php

namespace App\Providers;

use App\Mail\UserCreated;
use App\Models\Products;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Schema::defaultStringLength(191);

        Products::updated(function($product) {
            if ($product->quantity == 0 && $product->estaDisponible()) {
                $product->status = Products::PRODUCTO_NO_DISPONIBLE;

                $product->save();
            }
        });

        /* User::created(function($user) {
            Mail::to($user)->send(new UserCreated($user));
        }); */
    }
}
