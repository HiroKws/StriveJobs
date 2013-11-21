<?php

namespace StriveJobs\Commands;

use StriveJobs\Commands\BaseCommand;
use Symfony\Component\Console\Input\InputArgument;

class DoJob extends BaseCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'StriveJobs:do';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Execute a job.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        // Get API
        $striveJobs = \App::make( 'StriveJobs\StriveJobs' );

        // Check ID if exist
        $id = $this->argument( 'id' );

        if( !$striveJobs->isExistJobs( $id ) )
        {
            $this->error( 'ID is not exist.' );
            return 1;
        }

        // Execute specified job.
        if( ($result = $striveJobs->executeJob( $id )) )
        {
            $this->info( 'Executed successfully.' );
        }
        else
        {
            $this->error( 'Faild to execute.' );
        }

        if( ($message = $striveJobs->getMessage()) != '' )
        {
            $this->comment( $message );
        }

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
                'id',
                InputArgument::REQUIRED,
                'Job ID to execute.'
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
        );
    }

}