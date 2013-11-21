<?php

namespace StriveJobs\Commands;

use StriveJobs\Commands\BaseCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ShowJobs extends BaseCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'StriveJobs:show';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show jobs infomation.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        // Handling arguments and options
        $args = $this->argument();
        $opts = $this->option();

        $status = is_null( $args['status'] ) ? '' : $args['status'];
        $limit = $opts['take'];
        $oldestOrder = $opts['oldest'];

        // Don't use constructor to get a instance.
        // Because everytime make extra instance.
        $striveJobs = \App::make( 'StriveJobs\StriveJobs' );

        // call API
        $jobs = $striveJobs->getJobs( $status, $limit, $oldestOrder );

        // Display job records
        foreach( $jobs as $job )
        {
            $this->line( sprintf( "%d %s(%s) \"%s\"", $job['id'], $job['name'], $job['status'], $job["comment"] ) );
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
            array( 'status', InputArgument::OPTIONAL, 'Show only jobs specified status.' ),
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
                'take',
                't',
                InputOption::VALUE_OPTIONAL,
                'Only display specified job recode count.',
                0
            ),
            array(
                'oldest',
                'o',
                InputOption::VALUE_NONE,
                'Show oldest order.',
                null
            ),
        );
    }

}