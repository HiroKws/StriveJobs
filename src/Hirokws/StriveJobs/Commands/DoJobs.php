<?php

namespace StriveJobs\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class DoJobs extends Command
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
        // Get API
        $striveJobs = \App::make( 'StriveJobs\\StriveJobs' );

        // Check ID if exist
        $id = $this->argument( 'id' );

        if( !$striveJobs->isExistJobs( $id ) )
        {
            $this->error( 'ID is not exist.' );
            return;
        }

        // Execute specified job.
        if( !($result = $striveJobs->executeJob( $id )) )
        {
            $this->error( 'Faild to execute.' );
        }
        else
        {
            $this->info( 'Executed successfully.' );
        }

        if( ($message = $striveJobs->getMessage()) != '' )
        {
            $this->comment( $message );
        }

        return $result;
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

    /**
     * Set commnad main name.
     *
     * @param string $name Command main name.
     */
    public function setCommandName( $name )
    {
        $this->setName( str_replace( 'StriveJobs', $name, $this->name ) );
    }

}