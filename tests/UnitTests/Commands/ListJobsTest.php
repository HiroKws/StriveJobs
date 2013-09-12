<?php

use Mockery as m;
use StriveJobs\Commands\ListJobs;
use Symfony\Component\Console\Tester\CommandTester;

class ListJobsTest extends TestCase{

    public function testFireWithNoJob()
    {
        $mock = m::mock();
        $mock->shouldReceive( 'get' )
            ->once()
            ->andReturn( array( ) );

        App::instance( 'StriveJobs\\Services\\ListJobInfo', $mock );

        $tester = new CommandTester( new ListJobs );

        $tester->execute( array( ) );

        $this->assertEquals( "", $tester->getDisplay() );
    }

    public function testFireWithOneJob()
    {
        $class1 = new stdClass;
        $class1->number = 1;
        $class1->name = 'Name1';
        $class1->description = 'Desc1';

        $mock = m::mock();
        $mock->shouldReceive( 'get' )
            ->once()
            ->andReturn( array( $class1 ) );

        App::instance( 'StriveJobs\\Services\\ListJobInfo', $mock );

        $tester = new CommandTester( new ListJobs );

        $tester->execute( array( ) );

        $this->assertEquals( "1 Name1 Desc1\n", $tester->getDisplay() );
    }

    public function testFireWithTwoJob()
    {
        $class1 = new stdClass;
        $class1->number = 1;
        $class1->name = 'Name1';
        $class1->description = 'Desc1';

        $class2 = new stdClass;
        $class2->number = 2;
        $class2->name = 'Name2';
        $class2->description = 'Desc2';

        $mock = m::mock();
        $mock->shouldReceive( 'get' )
            ->once()
            ->andReturn( array( $class1, $class2 ) );

        App::instance( 'StriveJobs\\Services\\ListJobInfo', $mock );

        $tester = new CommandTester( new ListJobs );

        $tester->execute( array( ) );

        $this->assertEquals( "1 Name1 Desc1\n2 Name2 Desc2\n", $tester->getDisplay() );
    }

}