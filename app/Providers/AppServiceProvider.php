<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\DashboardController;

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
    public function boot(DashboardController $dashboard)
    {
        Schema::defaultStringLength(191);

        // View::composer('*', function ($view) {
        //     $test = $dashboard->notify();

        //     $view->with('test',$test);
        // });
        if (\Schema::hasTable('items')) {
            $stocksLowCount = $dashboard->getStocksLowCount();

            View::share('stocksLowCount', $stocksLowCount);
        }
    }
}
