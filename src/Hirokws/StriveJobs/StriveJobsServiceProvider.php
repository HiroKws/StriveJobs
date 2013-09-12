<?php

namespace StriveJobs;

use Illuminate\Support\ServiceProvider;
use StriveJobs\StriveJobs;
use StriveJobs\Commands\StartJob;
use StriveJobs\Commands\ResumeJobs;
use StriveJobs\Commands\ListJobs;

class StriveJobsServiceProvider extends ServiceProvider{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app['strivejobs'] = $this->app->share( function() {
                return new StriveJobs;
            }
        );
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        // Defined prefix for config, lang, views.
        $this->package( 'hirokws/strivejobs', 'StriveJobs' );

        // Register jobs
        $this->registerJobs();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array( );
    }

    private function registerJobs()
    {
        // Start command
        $this->app['strivejobs.startcommand'] = $this->app->share( function($app){
                return new StartJob( );
        } );

        // Resume command
        $this->app['strivejobs.resumecommand'] = $this->app->share( function($app){
                return new ResumeJobs( );
        } );

        // List job names command
        $this->app['strivejobs.listcommand'] = $this->app->share( function($app){
                return new ListJobs( );
        } );

        // Register all commands
        $this->commands(
            'strivejobs.startcommand', 'strivejobs.resumecommand', 'strivejobs.listcommand'
        );
    }

}