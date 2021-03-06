<?php

namespace StriveJobs\Commands\TestStub;

use StriveJobs\Commands\BaseCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class BaseCommandTestStub extends BaseCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'test:dummy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dummy stub command';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
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
                'param1',
                InputArgument::REQUIRED,
                'Dummy parameter'
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
                'option1',
                '',
                InputOption::VALUE_REQUIRED,
                'Target status',
                null
            ),
        );
    }

}