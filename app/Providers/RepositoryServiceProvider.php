<?php

namespace App\Providers;

use App\Repositories\Contracts\NotificationRepositoryInterface;
use App\Repositories\Eloquent\NotificationRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(NotificationRepositoryInterface::class,NotificationRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
