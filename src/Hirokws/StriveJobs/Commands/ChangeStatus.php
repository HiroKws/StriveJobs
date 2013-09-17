<?php

namespace StriveJobs\Commands;

use StriveJobs\Commands\BaseCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ChangeStatus extends BaseCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'StriveJobs:change';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change job status.';

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
            return 1;
        }

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

        // Get API instance
        $striveJobs = \App::make( 'StriveJobs\\StriveJobs' );

        // Dry run
        if( $args['dry'] )
        {
            $changeStatus = \App::make( 'StriveJobs\\Services\\ChangeStatusDryRun', array(
                    $striveJobs ) );
            $changeStatus->change( $this, $mode, $ids, $args['newStatus'] );

            return 0;
        }

        // Call change status API
        $affected = $striveJobs->changeJobStatus( $mode, $ids, $args['newStatus'] );

        $this->info( "Updated $affected job(s)." );

        return 0;
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array(
                'newStatus',
                InputArgument::REQUIRED,
                'New status for specifed jobs. Not run any methods in a job class.'
            ),
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
            array(
                'status',
                's',
                InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL,
                'Target status',
                null
            ),
            array(
                'id',
                'i',
                InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL,
                'An example option.',
                null
            ),
            array(
                'notId',
                'u',
                InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL,
                'Change for jobs not equale specified ID.',
                null
            ),
            array(
                'lessThan',
                null,
                InputOption::VALUE_OPTIONAL,
                'Execute for jobs less than specified ID.',
                null
            ),
            array(
                'lessThanEqual',
                'l',
                InputOption::VALUE_OPTIONAL,
                'Execute for jobs less than equale with specified ID.',
                null
            ),
            array(
                'greaterThan',
                null,
                InputOption::VALUE_OPTIONAL,
                'Execute for jobs greater than specified ID.',
                null
            ),
            array(
                'greaterThanEqual',
                'g',
                InputOption::VALUE_OPTIONAL,
                'Execute for jobs greater than or equale with specified ID.',
                null
            ),
            array(
                'dry',
                'd',
                InputOption::VALUE_NONE,
                'Dry run to check target jobs.',
                null
            ),
        );
    }

}