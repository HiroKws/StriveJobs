<?php

namespace StriveJobs\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class RegisterJob extends Command
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
        // Don't use constructor to get a instance.
        // Because everytime make extra instance.
        $striveJobs = \App::make( 'StriveJobs\\StriveJobs' );

        $jobClasses = $striveJobs->getJobClasses();

        // Get all argument
        $args = $this->argument();

        // Job argument handling
        $job = $args['job'];

        if( ( is_numeric( $job ) and ( $job < 1 or $job > count( $jobClasses )) ) or
            (!is_numeric( $job ) and !key_exists( $job, $jobClasses )) )
        {
            $this->error( 'Job is an integer or name. '.
                'Please confirm by ListJobs commnad.' );
            return;
        }

        // Arguments handling
        $arguments = array( );
        if( isset( $args['argument1'] ) ) $arguments['arg1'] = $args['argument1'];
        if( isset( $args['argument2'] ) ) $arguments['arg2'] = $args['argument2'];
        if( isset( $args['argument3'] ) ) $arguments['arg3'] = $args['argument3'];
        if( isset( $args['argument4'] ) ) $arguments['arg4'] = $args['argument4'];
        if( isset( $args['argument5'] ) ) $arguments['arg5'] = $args['argument5'];

        // Register this job.
        $id = $striveJobs->registerJob( $job, $this->option( 'comment' ), $arguments );

        $this->info( "Create new job. ID is $id." );
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

    /**
     * Set commnad main name.
     *
     * @param string $name Command main name.
     */
    public function setCommandName( $name )
    {
        $this->setName(str_replace( 'StriveJobs', $name, $this->name ));
    }

}