<?php
namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;
use Spatie\Activitylog\ActivitylogServiceProvider;
use App\Logging\CustomActivityLogger;
use Spatie\Activitylog\Models\Activity;
use App\Models\Attachment;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        // Bind the custom activity logger to the service container
        $this->app->bind(\Spatie\Activitylog\ActivityLogger::class, CustomActivityLogger::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // View composer to pass user profile image to all views
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

        // Handling the activity saving event to log IP address, referer, and response
        Activity::saving(function ($activity) {
            $activity->ip_address = Request::ip();
            $activity->referer = Request::header('referer');

            // Optionally, set a default value for response if it's not handled elsewhere
            $activity->response = 'Action performed successfully';
        });
    }
}
