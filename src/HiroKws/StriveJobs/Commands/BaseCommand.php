<?php

namespace StriveJobs\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BaseCommand extends Command
{

    /**
     * Override run method to internationalize error message.
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return int Return code
     */
    public function run( InputInterface $input, OutputInterface $output )
    {
        try
        {
            $result = parent::run( $input, $output );
        }
        catch( \RuntimeException $e )
        {
            // All error messages were hard coded in
            // Symfony/Component/Console/Input/Input.php
            if( $e->getMessage() == 'Not enough arguments.' )
            {
                $this->error( \Lang::get( 'StriveJobs::BaseCommand.ArgumentNotEnough' ) );
            }
            elseif( $e->getMessage() == 'Too many arguments.' )
            {
                $this->error( \Lang::get( 'StriveJobs::BaseCommand.TooManyArgument' ) );
            }
            elseif( preg_match( '/The "(.+)" option does not exist./', $e->getMessage(),
                                $matches ) )
            {
                $this->error( \Lang::get( 'StriveJobs::BaseCommand.OptionNotExist',
                                          array( 'option' => $matches[1] ) ) );
            }
            else
            {
                $this->error( $e->getMessage() );
            }
            $result = 1; // As error status code
        }

        return $result;
    }

    /**
     * Set commnad name.
     *
     * @param string $name Command main name.
     */
    public function setCommandName( $mainName, $subName )
    {
        $this->setName( $mainName.':'.$subName );
    }
// @hiro remove コマンドも必要、それに合わせパスワードの仕様も変更

}