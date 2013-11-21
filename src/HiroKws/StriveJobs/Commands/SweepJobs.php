<?php

namespace StriveJobs\Commands;

use StriveJobs\Commands\BaseCommand;

class SweepJobs extends BaseCommand
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
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        // Delete all jobs has terminated' status.
        $striveJobs = \App::make( 'StriveJobs\StriveJobs' );
        $affected = $striveJobs->deleteTerminatedJobs();

        if( $affected === false )
        {
            $this->error( 'Can\'t delete any jobs.' );
            return 1;
        }
        elseif( $affected > 0 )
        {
            $this->info( 'Deleted '.$affected.' terminated job(s).' );
        }
        else
        {
            $this->info( 'Nothing done.' );
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