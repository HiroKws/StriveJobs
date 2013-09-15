<?php

namespace StriveJobs\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class ResetJobs extends Command
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
        $password = trim( $this->secret( 'Password ? : ' ) );

        if( $this->option( 'hash' ) )
        {
            $this->info( \Hash::make( $password ) );
            return;
        }

        if( !\Hash::check( $password, \Config::get( 'StriveJobs::HashedResetPassword' ) ) )
        {
            $this->error( 'Entered password faild to match.' );
            return;
        }

        // Get API instance
        $striveJobs = \App::make( 'StriveJobs\\StriveJobs' );

        $striveJobs->truncateAllJob();

        $this->info( 'Reset all job.' );
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