<?php

namespace StriveJobs\Commands;

use Illuminate\Console\Command;

class ListJobs extends Command
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
        // Don't use constructor to get a instance.
        // Because everytime make extra instance.

        $jobInfoInstance = \App::make( 'StriveJobs\\Services\\ListJobInfo' );
        $jobInfo = $jobInfoInstance->get();

        foreach( $jobInfo as $info )
        {
            $this->line( sprintf(
                    '%d %s %s', $info->number, $info->name, $info->description ) );
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