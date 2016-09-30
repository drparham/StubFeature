<?php

namespace Drparham\StubFeature\Providers;

use Illuminate\Support\ServiceProvider;

class StubFeatureServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerFeatureGenerator();
    }

    /**
     * Register the make:feature generator.
     */
    private function registerFeatureGenerator()
    {
        $this->app->singleton('command.drparham.feature', function ($app) {
            return $app['Drparham\StubFeature\Commands\FeatureMakeCommand'];
        });
        $this->commands('command.drparham.feature');
    }
}
