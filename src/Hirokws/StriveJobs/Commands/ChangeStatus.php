<?php

namespace StriveJobs\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use StriveJobs\Services\Validations\ChangeStatusValidator;

class ChangeStatus extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'sj:change';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change job status.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        // Check Argument and Options.
        $args = array_merge( $this->option(), $this->argument() );

        $validator = \App::make( 'StriveJobs\\Services\\Validations\\ChangeStatusValidator' );

        $message = $validator->validate( $args );

        if( $message != '' )
        {
            $this->error( $message );
            return;
        }

        // Change status
        $striveJobs = \App::make( 'StriveJobs\\StriveJobs' );

        // Specified --status
        if( !empty( $args['status'] ) )
        {
            $mode = 'status';
            $ids = $args['status'];
        }

        // Specified --id
        if( !empty( $args['id'] ) )
        {
            $mode = 'equal';
            $ids = $args['id'];
        }

        // Specified --notId
        if( !empty( $args['notId'] ) )
        {
            $mode = "notEqual";
            $ids = $args['notId'];
        }

        // Specified --lessThan
        if( !is_null( $args['lessThan'] ) )
        {
            $mode = "lessThan";
            $ids = $args['lessThan'];
        }

        // Specified --lessThanEqual
        if( !is_null( $args['lessThanEqual'] ) )
        {
            $mode = "lessThanEqual";
            $ids = $args['lessThanEqual'];
        }

        // Specified --greaterThan
        if( !is_null( $args['greaterThan'] ) )
        {
            $mode = "greaterThan";
            $ids = $args['greaterThan'];
        }

        // Specified --greaterThanEqual
        if( !is_null( $args['greaterThanEqual'] ) )
        {
            $mode = "greaterThanEqual";
            $ids = $args['greaterThanEqual'];
        }

        if( $args['dryRun'] )
        {
            $jobs = $striveJobs->getJobsWithMode( $mode, $ids );

            if( $jobs === false )
            {
                $this->error( 'Logical error happened in change status command.' );
                return;
            }

            $this->info( "Following job's status will be change to {$args['newStatus']}." );

            foreach( $jobs as $job )
            {
                $this->info( sprintf( "%d %s(%s) \"%s\"", $job['id'], $job['name'], $job['status'], $job['comment'] ) );
            }
            return;
        }

        $striveJobs->changeStatus( $args['newStatus'], $mode, $ids );
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array( 'newStatus', InputArgument::REQUIRED, 'New status for specifed jobs' ),
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
            array( 'status', 's', InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL, 'Target status', null ),
            array( 'id', 'i', InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL, 'An example option.', null ),
            array( 'notId', 'u', InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL, 'Change for jobs not equale specified ID.', null ),
            array( 'lessThan', null, InputOption::VALUE_OPTIONAL, 'Execute for jobs less than specified ID.', null ),
            array( 'lessThanEqual', 'l', InputOption::VALUE_OPTIONAL, 'Execute for jobs less than equale with specified ID.', null ),
            array( 'greaterThan', null, InputOption::VALUE_OPTIONAL, 'Execute for jobs greater than specified ID.', null ),
            array( 'greaterThanEqual', 'g', InputOption::VALUE_OPTIONAL, 'Execute for jobs greater than or equale with specified ID.', null ),
            array( 'dryRun', 'd', InputOption::VALUE_NONE, 'Dry run to check target jobs.', null ),
        );
    }

}