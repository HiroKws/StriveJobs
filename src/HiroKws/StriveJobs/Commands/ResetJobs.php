<?php

namespace StriveJobs\Commands;

use StriveJobs\Commands\BaseCommand;
use Symfony\Component\Console\Input\InputOption;

class ResetJobs extends BaseCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'StriveJobs:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset all jobs.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire() // @hiro パスワードを引数でも
    {
        // Get password.
        $password = trim( $this->secret( 'Password ? : ' ) );

        // Hashed password display mode.
        if( $this->option( 'hash' ) )
        {
            $this->info( \Hash::make( $password ) );
            return 0;
        }

        // Check password.
        if( !\Hash::check( $password, \Config::get( 'StriveJobs::HashedResetPassword' ) ) )
        {
            $this->error( 'Entered password faild to match.' );
            return 1;
        }

        // Truncate jobs table.
        $striveJobs = \App::make( 'StriveJobs\StriveJobs' );
        $striveJobs->truncateAllJob();

        $this->info( 'Reset all job.' );

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
                'hash',
                '?',
                InputOption::VALUE_NONE,
                'Show hashed value.',
                null
            ),
        );
    }

}