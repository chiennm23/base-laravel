<?php

namespace App\Providers;

use App\Repositories\Customers\CustomerRepository;
use App\Repositories\Customers\CustomerRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class CustomerServiceProvider extends BaseRepositoryServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(CustomerRepositoryInterface::class, CustomerRepository::class);
    }
}
