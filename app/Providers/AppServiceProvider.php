<?php

namespace App\Providers;

use App\Notifications;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $notifications = Notifications::where([['notify_type', '!=', 'provider'], ['status', 'active']])
            ->orderBy('created_at', 'desc')
            ->get();
        View::share('globalNotifications', $notifications);
        Carbon::setLocale('pt_BR');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
