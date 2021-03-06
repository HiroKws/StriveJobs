<?php

use Mockery as m;
use StriveJobs\TestCase;
use StriveJobs\Commands\ListJobs;
use Symfony\Component\Console\Tester\CommandTester;

class ChangeStatusTest extends TestCase
{

    public function testFireWith()
    {
        $mock = m::mock();
        $mock->shouldReceive( 'get' )
            ->once()
            ->andReturn( array( ) );

        App::instance( 'StriveJobs\Services\ListJobInfo', $mock );

        $tester = new CommandTester( new ListJobs );
        $tester->execute( array( ) );

        $this->assertEquals( "", $tester->getDisplay() );
    }

}