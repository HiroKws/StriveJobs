<?php

use Mockery as m;
use StriveJobs\TestCase;
use StriveJobs\Services\ChangeStatusDryRun;

class ChageStatusDryRunTest extends TestCase
{

    public function testChange()
    {
        $mode = 'Test Mode';
        $ids = array( 111, 222 );
        $newStatus = 'TestStatus';

        $job1 = array(
            'id'      => 111,
            'name'    => 'Test1',
            'status'  => 'Status1',
            'comment' => 'Comment1'
        );
        $job2 = array(
            'id'      => 222,
            'name'    => 'Test2',
            'status'  => 'Status2',
            'comment' => 'Comment2'
        );
        $jobs = array( $job1, $job2 );

        $mock = m::mock(); // parent
        $mock->shouldReceive( 'info' )
            ->once()
            ->with( m::type( 'string' ) );
        $mock->shouldReceive( 'line' )
            ->twice()
            ->with( m::type( 'string' ) );

        $striveJobsMock = m::mock( 'StriveJobs\\StriveJobs' );
        $striveJobsMock->shouldReceive( 'getJobsWithMode' )
            ->once()
            ->with( $mode, $ids )
            ->andReturn( $jobs );

        $dryRun = new ChangeStatusDryRun( $striveJobsMock );

        $dryRun->change( $mock, $mode, $ids, $newStatus );
    }

    public function testChangeJobError()
    {
        $mode = 'Test Mode';
        $ids = array( 111, 222 );
        $newStatus = 'TestStatus';

        $mock = m::mock(); // parent
        $mock->shouldReceive( 'error' )
            ->once()
            ->with( m::type( 'string' ) );

        $striveJobsMock = m::mock( 'StriveJobs\\StriveJobs' );
        $striveJobsMock->shouldReceive( 'getJobsWithMode' )
            ->once()
            ->with( $mode, $ids )
            ->andReturn( false );

        $dryRun = new ChangeStatusDryRun( $striveJobsMock );

        $dryRun->change( $mock, $mode, $ids, $newStatus );
    }

}