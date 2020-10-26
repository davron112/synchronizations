<?php

namespace Davron112\Synchronizations\Providers;

use Illuminate\Support\ServiceProvider;
use Davron112\Synchronizations\Jobs\Contracts\ProductSynchronization as ProductSynchronizationInterface;
use Davron112\Synchronizations\Jobs\ProductSynchronization;

/**
 * Class SynchronizationsServiceProvider
 * @package Davron112\Synchronizations\Providers
 */
class SynchronizationsServiceProvider extends ServiceProvider
{
    /**
     * Boot the application services.
     *
     * @return void
     */
    public function boot()
    {
        //publish migrations
        $this->publishes([
            __DIR__ . '/../../database/migrations' => database_path('migrations'),
        ], 'database');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ProductSynchronizationInterface::class, ProductSynchronization::class);
    }
}
