<?php

namespace StriveJobs\Commands;

use Illuminate\Console\Command;

class SweepJobs extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'StriveJobs:sweep';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete all \'terminated\' job.';

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
        // Get API instance
        $striveJobs = \App::make( 'StriveJobs\\StriveJobs' );

        $affected = $striveJobs->deleteTerminatedJobs();

        if( $affected === false )
        {
            $this->error( 'Can\'t delete any jobs.' );
        }
        elseif( $affected > 0 )
        {
            $this->info( 'Deleted '.$affected.' terminated job(s).' );
        }
        else
        {
            $this->info( 'Nothing done.' );
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
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