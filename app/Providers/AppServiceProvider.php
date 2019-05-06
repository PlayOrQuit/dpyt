<?php

namespace App\Providers;

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
        $this->app->bind(
            'App\Data\Repository\DataKeyRepository',
            'App\Data\Repository\Impl\DataKeyRepositoryImpl'
        );
        $this->app->bind(
            'App\Data\Repository\ChannelRepository',
            'App\Data\Repository\Impl\ChannelRepositoryImpl'
        );
        $this->app->bind(
            'App\Data\Repository\PlaylistRepository',
            'App\Data\Repository\Impl\PlaylistRepositoryImpl'
        );
        $this->app->bind(
            'App\Data\Repository\PlaylistItemRepository',
            'App\Data\Repository\Impl\PlaylistItemRepositoryImpl'
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }
}
