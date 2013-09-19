<?php

use Mockery as m;
use StriveJobs\Services\ListJobInfo;
use StriveJobs\TestCase;

class ListJobInfoTest extends TestCase
{

    public function testGetClasses()
    {
        $mock1 = m::mock();
        $mock1->shouldReceive( 'getName' )
            ->andReturn( 'Name1' );
        $mock1->shouldReceive( 'getDescription' )
            ->andReturn( 'Desc1' );

        $mock2 = m::mock();
        $mock2->shouldReceive( 'getName' )
            ->andReturn( 'Name2' );
        $mock2->shouldReceive( 'getDescription' )
            ->andReturn( 'Desc2' );

        $striveJobsMock = m::mock( 'StriveJobs\\StriveJobs' );
        $striveJobsMock->shouldReceive( 'getJobClasses' )
            ->once()
            ->andReturn( array( $mock1, $mock2 ) );

        $listJobInfo = new ListJobInfo( $striveJobsMock );

        $ret1 = new stdClass;
        $ret1->number = 1;
        $ret1->name = 'Name1';
        $ret1->description = 'Desc1';

        $ret2 = new stdClass;
        $ret2->number = 2;
        $ret2->name = 'Name2';
        $ret2->description = 'Desc2';

        $this->assertEquals( array( $ret1, $ret2 ), $listJobInfo->get() );
    }

}