<?php

use Mockery as m;
use StriveJobs\Commands\ShowJobs;
use Symfony\Component\Console\Tester\CommandTester;

class ShowJobsTest extends TestCase
{

    public function testFire()
    {
        $striveJobsMock = m::mock( 'StriveJobs\\StriveJobs' );
        $striveJobsMock->shouldReceive( 'getJobs' )
            ->once()
            ->with( '', 0, false )
            ->andReturn( array( array(
                    'id' => 1,
                    'name' => 'Job1',
                    'status' => 'registered',
                    'comment' => 'Comm'
            ) ) );

        App::instance( 'StriveJobs\\StriveJobs', $striveJobsMock );

        $tester = new CommandTester( new ShowJobs );
        $tester->execute( array( ) );

        $this->assertEquals( "1 Job1(registered) \"Comm\"\n", $tester->getDisplay() );
    }

    public function testFireWithMulitpleJobsDisplay()
    {
        $striveJobsMock = m::mock( 'StriveJobs\\StriveJobs' );
        $striveJobsMock->shouldReceive( 'getJobs' )
            ->once()
            ->with( '', 0, false )
            ->andReturn( array(
                array(
                    'id' => 1,
                    'name' => 'Job1',
                    'status' => 'registered',
                    'comment' => 'Comm1'
                ),
                array(
                    'id' => 2,
                    'name' => 'Job2',
                    'status' => 'terminated',
                    'comment' => 'Comm2'
                ),
                array(
                    'id' => 3,
                    'name' => 'Job3',
                    'status' => 'suspended',
                    'comment' => 'Comm3'
                )
            ) );

        App::instance( 'StriveJobs\\StriveJobs', $striveJobsMock );

        $tester = new CommandTester( new ShowJobs );
        $tester->execute( array( ) );

        $this->assertEquals( "1 Job1(registered) \"Comm1\"\n2 Job2(terminated) \"Comm2\"\n3 Job3(suspended) \"Comm3\"\n", $tester->getDisplay() );
    }

    public function testFireWithStatus()
    {
        $striveJobsMock = m::mock( 'StriveJobs\\StriveJobs' );
        $striveJobsMock->shouldReceive( 'getJobs' )
            ->once()
            ->with( 'Condition', 0, false ) // check only argument
            ->andReturn( array( array(
                    'id' => 1,
                    'name' => 'Job1',
                    'status' => 'registered',
                    'comment' => 'Comm'
            ) ) );

        App::instance( 'StriveJobs\\StriveJobs', $striveJobsMock );

        $tester = new CommandTester( new ShowJobs );
        $tester->execute( array( 'status' => 'Condition' ) );
    }

    public function testFireWithT()
    {
        $striveJobsMock = m::mock( 'StriveJobs\\StriveJobs' );
        $striveJobsMock->shouldReceive( 'getJobs' )
            ->once()
            ->with( '', 100, false ) // check only argument
            ->andReturn( array( array(
                    'id' => 1,
                    'name' => 'Job1',
                    'status' => 'registered',
                    'comment' => 'Comm'
            ) ) );

        App::instance( 'StriveJobs\\StriveJobs', $striveJobsMock );

        $tester = new CommandTester( new ShowJobs );
        $tester->execute( array( '-t' => '100' ) );
    }

    public function testFireWithTake()
    {
        $striveJobsMock = m::mock( 'StriveJobs\\StriveJobs' );
        $striveJobsMock->shouldReceive( 'getJobs' )
            ->once()
            ->with( '', 200, false ) // check only argument
            ->andReturn( array( array(
                    'id' => 1,
                    'name' => 'Job1',
                    'status' => 'registered',
                    'comment' => 'Comm'
            ) ) );

        App::instance( 'StriveJobs\\StriveJobs', $striveJobsMock );

        $tester = new CommandTester( new ShowJobs );
        $tester->execute( array( '--take' => '200' ) );
    }

    public function testFireWithOldest()
    {
        $striveJobsMock = m::mock( 'StriveJobs\\StriveJobs' );
        $striveJobsMock->shouldReceive( 'getJobs' )
            ->once()
            ->with( '', 0, true ) // check only argument
            ->andReturn( array( array(
                    'id' => 1,
                    'name' => 'Job1',
                    'status' => 'registered',
                    'comment' => 'Comm'
            ) ) );

        App::instance( 'StriveJobs\\StriveJobs', $striveJobsMock );

        $tester = new CommandTester( new ShowJobs );
        // In tester environment, must specify 'true' manually
        // when use 'VALUE_NONE' type option.
        $tester->execute( array( '--oldest' => 'true' ) );
    }

}