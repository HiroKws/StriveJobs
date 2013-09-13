<?php

namespace StriveJobs\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ShowJobs extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'sj:show';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show jobs infomation.';

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
        // Handling arguments and options

        // Call API

        // Display job records
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
            array( 'n', null, InputOption::VALUE_OPTIONAL, 'Only display specified job recodes.', 0 ),
            array( 'latest', null, InputOption::VALUE_NONE, 'Show latest order.', null ),
        );
    }

}