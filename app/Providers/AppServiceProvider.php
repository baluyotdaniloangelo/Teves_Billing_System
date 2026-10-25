<?php

namespace App\Providers;

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
        $this->app->singleton(\App\Services\UserActivityService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $activityModel = config('activitylog.activity_model', \Spatie\Activitylog\Models\Activity::class);
        $activityModel::saving(function ($activity) {
            app(\App\Services\UserActivityService::class)->prepare($activity);
        });
    }
}
