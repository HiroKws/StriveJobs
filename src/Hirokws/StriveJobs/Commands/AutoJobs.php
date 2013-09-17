<?php

namespace StriveJobs\Commands;

use Symfony\Component\Console\Input\InputOption;
use StriveJobs\Commands\BaseCommand;

class AutoJobs extends BaseCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'StriveJobs:auto';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatic execute jobs by specified rules.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $args = $this->option();

        $validator =
            \App::make( 'StriveJobs\\Services\\Validations\\AutoExecutionValidator' );
        $message = $validator->validate( $args );

        if( $message != '' )
        {
            $this->error( $message );
            return 1;
        }

        if( $args['latest'] )
        {
            $rules = '';
        }
        elseif( !empty( $args['rule'] ) )
        {
            $rules = array( );

            foreach( $args['rule'] as $rule )
            {
                list($status, $sort) = explode( ':', $rule );
                $rules[$status] = $sort;
            }
        }
        else
        {
            $rules = 'Ascending';
        }

        if( $args['dry'] )
        {
            $dryRun = \App::make( 'StriveJobs\\Services\\AutoExecutionDryRun' );
            $message = $dryRun->run( $this, $rules, $args['execute'] );

            return 0;
        }

        $striveJobs = \App::make( 'StriveJobs\\StriveJobs' );
        $striveJobs->executeByRules( $rules, $args['execute'] );

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
            array(
                'latest',
                'l',
                InputOption::VALUE_NONE,
                'Put high priority with later jobs.',
                null
            ),
            array(
                'rule',
                'r',
                InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
                'Select rule : "status:l" or "status:o". (Multiple)',
                null
            ),
            array(
                'execute',
                'e',
                InputOption::VALUE_OPTIONAL,
                'Specify how many job will execute.',
                1
            ),
            array(
                'dry',
                'd',
                InputOption::VALUE_NONE,
                'Dry run to confirm which job will execute',
                null
            ),
        );
    }

}