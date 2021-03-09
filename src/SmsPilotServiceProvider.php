<?php

namespace Chevgenio\SmsPilot;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class SmsPilotServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(SmsPilotApi::class, function ($app) {
            return new SmsPilotApi($app['config']['services.smspilot']);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return [SmsPilotApi::class];
    }
}
