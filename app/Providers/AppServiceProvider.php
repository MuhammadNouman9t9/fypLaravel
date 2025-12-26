<?php

namespace App\Providers;

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
        // Share unread support messages count with all views for authenticated users
        view()->composer('*', function ($view) {
            if (auth()->check() && auth()->user()) {
                try {
                    $view->with('unreadSupportCount', auth()->user()->getUnreadSupportMessagesCount());
                } catch (\Exception $e) {
                    $view->with('unreadSupportCount', 0);
                }
            } else {
                $view->with('unreadSupportCount', 0);
            }
        });
    }
}
