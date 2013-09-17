<?php

namespace StriveJobs\Commands;

use StriveJobs\Commands\BaseCommand;

class ListJobs extends BaseCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'StriveJobs:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all job\'s descriptions.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        // Get all job classes.
        $jobInfoInstance = \App::make( 'StriveJobs\\Services\\ListJobInfo' );
        $jobInfo = $jobInfoInstance->get();

        foreach( $jobInfo as $info )
        {
            $this->line( sprintf(
                    '%d %s %s', $info->number, $info->name, $info->description ) );
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