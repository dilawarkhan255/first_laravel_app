<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Models\Attachment;

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
        view()->composer('*', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();
                $user_profile_image = Attachment::where('attachable_id', $user->id)
                                                ->where('type', 'profile')
                                                ->first();
                $view->with('user_profile_image', $user_profile_image);
            } else {
                $view->with('user_profile_image', null);
            }
        });
    }
}
