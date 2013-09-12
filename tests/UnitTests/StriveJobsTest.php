<?php

use Mockery as m;
use StriveJobs\StriveJobs;

class StriveJobsTest extends TestCase{

    public function testRegisterAndGetter()
    {
        $mock1 = m::mock( 'stdClass, StriveJobs\\StriveJobsInterface' );
        $mock1->shouldReceive( 'getName' )
            ->once()
            ->andReturn( 'Name1' );
        $mock1->shouldReceive( 'getDescription' )
            ->once()
            ->andReturn( 'Desc1' );

        $mock2 = m::mock( 'stdClass, StriveJobs\\StriveJobsInterface' );
        $mock2->shouldReceive( 'getName' )
            ->once()
            ->andReturn( 'Name2' );
        $mock2->shouldReceive( 'getDescription' )
            ->once()
            ->andReturn( 'Desc2' );

        $striveJobs = new StriveJobs;
        $striveJobs->register( array( $mock1, $mock2 ) );

        $excepted = array(
            'Name1' => $mock1,
            'Name2' => $mock2
        );
        $this->assertEquals( $excepted, $striveJobs->getJobClasses() );
    }

    public function testRegisterAndGetterWithOneClass()
    {
        $mock1 = m::mock( 'stdClass, StriveJobs\\StriveJobsInterface' );
        $mock1->shouldReceive( 'getName' )
            ->once()
            ->andReturn( 'Name1' );
        $mock1->shouldReceive( 'getDescription' )
            ->once()
            ->andReturn( 'Desc1' );

        $striveJobs = new StriveJobs;
        $striveJobs->register( $mock1 );

        $excepted = array(
            'Name1' => $mock1
        );
        $this->assertEquals( $excepted, $striveJobs->getJobClasses() );
    }

}