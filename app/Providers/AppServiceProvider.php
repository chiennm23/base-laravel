<?php

namespace App\Providers;

use App\Http\Resources\Customers\CustomerCollection;
use App\Http\Resources\Products\ProductCollection;
use Illuminate\Http\Resources\Json\JsonResource;
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
        JsonResource::withoutWrapping();
        ProductCollection::withoutWrapping();
        CustomerCollection::withoutWrapping();
    }
}
