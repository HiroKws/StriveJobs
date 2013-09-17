<?php

namespace StriveJobs;

use Illuminate\Support\ServiceProvider;
use StriveJobs\StriveJobs;
use StriveJobs\Commands\DoJob;
use StriveJobs\Commands\ListJobs;
use StriveJobs\Commands\RegisterJob;
use StriveJobs\Commands\ShowJobs;
use StriveJobs\Commands\ChangeStatus;
use StriveJobs\Commands\SweepJobs;
use StriveJobs\Commands\ResetJobs;
use StriveJobs\Commands\AutoJobs;
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

        // Set commnad name.
        $this->setCommandName();
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
            'strivejobs.registercommand',
            'strivejobs.listcommand',
            'strivejobs.showcommand',
            'strivejobs.changecommand',
            'strivejobs.docommand',
            'strivejobs.sweepcommand',
            'strivejobs.resetcommand',
            'strivejobs.autocommand'
        );
    }

    private function registerCommands()
    {

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

        // Execute job status commnand
        $this->app['strivejobs.docommand'] = $this->app->share( function($app)
            {
                return new DoJob( );
            } );

        // Sweep out 'terminated' jobs.
        $this->app['strivejobs.sweepcommand'] = $this->app->share( function($app)
            {
                return new SweepJobs( );
            } );

        // Reset all jobs.
        $this->app['strivejobs.resetcommand'] = $this->app->share( function($app)
            {
                return new ResetJobs( );
            } );

        // Execute automatically selected jobs by rules.
        $this->app['strivejobs.autocommand'] = $this->app->share( function($app)
            {
                return new AutoJobs( );
            } );

        // Register all commands
        $this->commands(
            'strivejobs.listcommand', 'strivejobs.registercommand', 'strivejobs.showcommand', 'strivejobs.changecommand', 'strivejobs.docommand', 'strivejobs.sweepcommand', 'strivejobs.resetcommand', 'strivejobs.autocommand'
        );
    }

    /**
     * Set command main name from MainCommandName item of config.php.
     *
     */
    private function setCommandName()
    {
        $main = \Config::get( 'StriveJobs::MainCommandName' );
        
        $this->app['strivejobs.registercommand']
            ->setCommandName( $main, \Config::get( 'StriveJobs::SubCommandNames.Register' ) );

        $this->app['strivejobs.listcommand']
            ->setCommandName( $main, \Config::get( 'StriveJobs::SubCommandNames.List' ) );

        $this->app['strivejobs.showcommand']
            ->setCommandName( $main, \Config::get( 'StriveJobs::SubCommandNames.Show' ) );

        $this->app['strivejobs.changecommand']
            ->setCommandName( $main, \Config::get( 'StriveJobs::SubCommandNames.Change' ) );

        $this->app['strivejobs.docommand']
            ->setCommandName( $main, \Config::get( 'StriveJobs::SubCommandNames.Do' ) );

        $this->app['strivejobs.sweepcommand']
            ->setCommandName( $main, \Config::get( 'StriveJobs::SubCommandNames.Sweep' ) );

        $this->app['strivejobs.resetcommand']
            ->setCommandName( $main, \Config::get( 'StriveJobs::SubCommandNames.Reset' ) );

        $this->app['strivejobs.autocommand']
            ->setCommandName( $main, \Config::get( 'StriveJobs::SubCommandNames.Auto' ) );
    }

}