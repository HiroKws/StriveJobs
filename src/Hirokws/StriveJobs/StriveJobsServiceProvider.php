<?php

namespace StriveJobs;

use Illuminate\Support\ServiceProvider;
use StriveJobs\StriveJobs;
use StriveJobs\Commands\StartJob;
use StriveJobs\Commands\ResumeJobs;
use StriveJobs\Commands\ListJobs;
use StriveJobs\Commands\RegisterJob;
use StriveJobs\Commands\ShowJobs;
use StriveJobsCommands\ChangeStatus;
use StriveJobs\Repositories\StriveJobsEloquentRepository;

class StriveJobsServiceProvider extends ServiceProvider
{
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
        $this->app['StriveJobs\\StriveJobs'] = $this->app->share( function()
            {
                return new StriveJobs;
            }
        );

        $this->app['StriveJobs\\Repositories\\JobsRepositoryInterface'] =
            $this->app->share( function ($app)
            {
                return new StriveJobsEloquentRepository(
                    $app['StriveJobs\\EloquentModels\\StriveJob'] );
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

        // Register commands
        $this->registerCommands();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array(
            'StriveJobs\\StriveJobs',
            'strivejobs.startcommand',
            'strivejobs.resumecommand',
            'strivejobs.registercommand',
            'strivejobs.listcommand',
            'strivejobs.showcommand' );
    }

    private function registerCommands()
    {
        // Start command
        $this->app['strivejobs.startcommand'] = $this->app->share( function($app)
            {
                return new StartJob( );
            } );

        // Resume command
        $this->app['strivejobs.resumecommand'] = $this->app->share( function($app)
            {
                return new ResumeJobs( );
            } );

        // Register command
        $this->app['strivejobs.registercommand'] = $this->app->share( function($app)
            {
                return new RegisterJob( );
            } );

        // List job names command
        $this->app['strivejobs.listcommand'] = $this->app->share( function($app)
            {
                return new ListJobs( );
            } );

       // Show jobs commnand
        $this->app['strivejobs.showcommand'] = $this->app->share( function($app)
            {
                return new ShowJobs( );
            } );

       // Change job status commnand
        $this->app['strivejobs.changecommand'] = $this->app->share( function($app)
            {
                return new ChangeStatus( );
            } );

        // Register all commands
        $this->commands(
            'strivejobs.startcommand',
            'strivejobs.resumecommand',
            'strivejobs.listcommand',
            'strivejobs.registercommand',
            'strivejobs.showcommand',
            'strivejobs.changecommand'
        );
    }

}