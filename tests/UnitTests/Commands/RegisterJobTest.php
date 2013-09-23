<?php

use Mockery as m;
use StriveJobs\Commands\RegisterJob;
use Symfony\Component\Console\Tester\CommandTester;
use StriveJobs\TestCase;

class RegisterJobTest extends TestCase
{

    public function testFireWithNoArgument()
    {
        $mock = m::mock( 'StriveJobs\\StriveJobs' );
        $mock->shouldReceive( 'getJobClasses' )
            ->never()
            ->andReturn( array( ) );

        App::instance( 'StriveJobs\\StriveJobs', $mock );

        $this->setExpectedException( 'RuntimeException' );

        $tester = new CommandTester( new RegisterJob );
        $tester->execute( array( ) );
    }

    public function testFireWithCorrectJobNumber()
    {
        $jobs = array( 'Job1' => '', 'Job2' => '', 'Job3' => '' );

        $mock = m::mock( 'StriveJobs\\StriveJobs' );
        $mock->shouldReceive( 'getJobClasses' )
            ->once()
            ->andReturn( $jobs );
        $mock->shouldReceive( 'registerJob' )
            ->once()
            ->with( '1', '', array( ), 0 )
            ->andReturn( 11 );

        App::instance( 'StriveJobs\\StriveJobs', $mock );

        $tester = new CommandTester( new RegisterJob );
        $tester->execute( array( 'job' => '1' ) );

        $this->assertEquals( "Create new job. ID is 11.\n", $tester->getDisplay() );
    }

    public function testFireWithCorrectJobName()
    {
        $jobs = array( 'Job1' => '', 'Job2' => '', 'Job3' => '' );

        $mock = m::mock( 'StriveJobs\\StriveJobs' );
        $mock->shouldReceive( 'getJobClasses' )
            ->once()
            ->andReturn( $jobs );
        $mock->shouldReceive( 'registerJob' )
            ->once()
            ->with( 'Job2', '', array( ), 0 )
            ->andReturn( 12 );

        App::instance( 'StriveJobs\\StriveJobs', $mock );

        $tester = new CommandTester( new RegisterJob );
        $tester->execute( array( 'job' => 'Job2' ) );

        $this->assertEquals( "Create new job. ID is 12.\n", $tester->getDisplay() );
    }

    public function testFireWithCorrectJobNameAndArguments()
    {
        $jobs = array( 'Job1' => '', 'Job2' => '', 'Job3' => '' );

        $mock = m::mock( 'StriveJobs\\StriveJobs' );
        $mock->shouldReceive( 'getJobClasses' )
            ->once()
            ->andReturn( $jobs );
        $mock->shouldReceive( 'registerJob' )
            ->once()
            ->with( 'Job3', '', array(
                'arg1' => 'Arg1',
                'arg2' => 'Arg2',
                'arg3' => 'Arg3',
                'arg4' => 'Arg4',
                'arg5' => 'Arg5' ), 0 )
            ->andReturn( 13 );

        App::instance( 'StriveJobs\\StriveJobs', $mock );

        $tester = new CommandTester( new RegisterJob );
        $tester->execute( array( 'job'       => 'Job3', 'argument1' => 'Arg1', 'argument2' => 'Arg2',
            'argument3' => 'Arg3', 'argument4' => 'Arg4', 'argument5' => 'Arg5' ) );

        $this->assertEquals( "Create new job. ID is 13.\n", $tester->getDisplay() );
    }

    public function testFireWithNoExistJobNumber()
    {
        $jobs = array( 'Job1' => '', 'Job2' => '', 'Job3' => '' );

        $mock = m::mock( 'StriveJobs\\StriveJobs' );
        $mock->shouldReceive( 'getJobClasses' )
            ->once()
            ->andReturn( $jobs );
        $mock->shouldReceive( 'registerJob' )
            ->never();

        App::instance( 'StriveJobs\\StriveJobs', $mock );

        $tester = new CommandTester( new RegisterJob );
        $tester->execute( array( 'job' => '4' ) );

        $this->assertEquals( trim( 'Job is an integer or name of job class.' ), trim( $tester->getDisplay() ) );
    }

    public function testFireWithLessThanOneJobNumber()
    {
        $jobs = array( 'Job1' => '', 'Job2' => '', 'Job3' => '' );

        $mock = m::mock( 'StriveJobs\\StriveJobs' );
        $mock->shouldReceive( 'getJobClasses' )
            ->once()
            ->andReturn( $jobs );
        $mock->shouldReceive( 'registerJob' )
            ->never();

        App::instance( 'StriveJobs\\StriveJobs', $mock );

        $tester = new CommandTester( new RegisterJob );
        $tester->execute( array( 'job' => '0' ) );

        $this->assertEquals( trim( 'Job is an integer or name of job class.' ), trim( $tester->getDisplay() ) );
    }

    public function testFireWithNoExistJobName()
    {
        $jobs = array( 'Job1' => '', 'Job2' => '', 'Job3' => '' );

        $mock = m::mock( 'StriveJobs\\StriveJobs' );
        $mock->shouldReceive( 'getJobClasses' )
            ->once()
            ->andReturn( $jobs );
        $mock->shouldReceive( 'registerJob' )
            ->never();

        App::instance( 'StriveJobs\\StriveJobs', $mock );

        $tester = new CommandTester( new RegisterJob );
        $tester->execute( array( 'job' => 'No Matched String' ) );

        $this->assertEquals( trim( 'Job is an integer or name of job class.' ), trim( $tester->getDisplay() ) );
    }

    public function testFireWithComment()
    {
        $jobs = array( 'Job1' => '', 'Job2' => '', 'Job3' => '' );

        $mock = m::mock( 'StriveJobs\\StriveJobs' );
        $mock->shouldReceive( 'getJobClasses' )
            ->once()
            ->andReturn( $jobs );
        $mock->shouldReceive( 'registerJob' )
            ->once()
            ->with( '1', 'Comm', array( ), 0 )
            ->andReturn( 20 );

        App::instance( 'StriveJobs\\StriveJobs', $mock );

        $tester = new CommandTester( new RegisterJob );
        $tester->execute( array( 'job'       => '1', '--comment' => 'Comm' ) );

        $this->assertEquals( "Create new job. ID is 20.\n", $tester->getDisplay() );
    }

}