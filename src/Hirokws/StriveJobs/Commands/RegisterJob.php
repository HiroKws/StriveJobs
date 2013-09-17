<?php

namespace StriveJobs\Commands;

use StriveJobs\Commands\BaseCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class RegisterJob extends BaseCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'StriveJobs:register';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Register new job.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        // Get all job classes.
        $striveJobs = \App::make( 'StriveJobs\\StriveJobs' );
        $jobClasses = $striveJobs->getJobClasses();

        // Get all argument
        $args = $this->argument();

        // Check if exist specified class.
        $job = $args['job'];

        if( ( is_numeric( $job ) and ( $job < 1 or $job > count( $jobClasses )) ) or
            (!is_numeric( $job ) and !key_exists( $job, $jobClasses )) )
        {
            $this->error( 'Job is an integer or name of job class. ' );
            return 1;
        }

        // Arguments handling
        $arguments = array( );

        for( $i = 1; $i <= 5; $i++ )
        {
            if( isset( $args['argument'.$i] ) ) $arguments['arg'.$i] = $args['argument'.$i];
        }

        // Register this job.
        $id = $striveJobs->registerJob( $job, $this->option( 'comment' ), $arguments );

        $this->info( "Create new job. ID is $id." );

        return 0; ;
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array( 'job', InputArgument::REQUIRED, 'Job Number or name. Do & see ListJobs command.' ),
            array( 'argument1', InputArgument::OPTIONAL, 'An argument, if needed.' ),
            array( 'argument2', InputArgument::OPTIONAL, 'An argument, if needed.' ),
            array( 'argument3', InputArgument::OPTIONAL, 'An argument, if needed.' ),
            array( 'argument4', InputArgument::OPTIONAL, 'An argument, if needed.' ),
            array( 'argument5', InputArgument::OPTIONAL, 'An argument, if needed.' ),
        );
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(
            array( 'comment', null, InputOption::VALUE_OPTIONAL, 'A comment', '' ),
        );
    }

}