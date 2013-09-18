<?php

use Mockery as m;
use StriveJobs\BaseJobClass;

class BaseJobClassTest extends TestCase
{

    public function testPutArguments()
    {
        $jobId = 328;
        $data = 'Dummy_9lc98';
        $result = 'Result_90d67';

        $mock = m::mock( 'StriveJobs\\StriveJobs' );
        $mock->shouldReceive( 'putArguments' )
            ->once()
            ->with( $jobId, $data )
            ->andReturn( $result );

        $baseJob = new BaseJobClass;
        $baseJob->jobId = $jobId;
        $baseJob->striveJobs = $mock;

        $this->assertEquals( $result, $baseJob->putArguments( $data ) );
    }

    public function testGetComment()
    {
        $comment = 'Dummy_(okDSH';

        $baseJob = new BaseJobClass;
        $baseJob->comment = $comment;

        $this->assertEquals( $comment, $baseJob->getComment() );
    }

    public function testPutComment()
    {
        $jobId = 267;
        $data = 'Dummy_br4yd';
        $result = 'Result_3dgsd';

        $mock = m::mock( 'StriveJobs\\StriveJobs' );
        $mock->shouldReceive( 'putComment' )
            ->once()
            ->with( $jobId, $data )
            ->andReturn( $result );

        $baseJob = new BaseJobClass;
        $baseJob->jobId = $jobId;
        $baseJob->striveJobs = $mock;

        $this->assertEquals( $result, $baseJob->putComment( $data ) );
        $this->assertEquals( $result, $baseJob->comment );
    }

    public function testRemoveMe()
    {
        $jobId = 176;
        $result = 'Result_89vjd';

        $mock = m::mock( 'StriveJobs\\StriveJobs' );
        $mock->shouldReceive( 'removeJobs' )
            ->once()
            ->with( ( array ) $jobId )
            ->andReturn( $result );

        $baseJob = new BaseJobClass;
        $baseJob->jobId = $jobId;
        $baseJob->striveJobs = $mock;

        $this->assertEquals( $result, $baseJob->removeMe() );
    }

    public function testKillMe()
    {
        $jobId = 90;
        $result = 'Result_ckc09';

        $mock = m::mock( 'StriveJobs\\StriveJobs' );
        $mock->shouldReceive( 'removeJobs' )
            ->once()
            ->with( ( array ) $jobId )
            ->andReturn( $result );

        $baseJob = new BaseJobClass;
        $baseJob->jobId = $jobId;
        $baseJob->striveJobs = $mock;

        $this->assertEquals( $result, $baseJob->killMe() );
    }

    public function testHarakiri()
    {
        $jobId = 1294;
        $result = 'Result_38Xd0';

        $mock = m::mock( 'StriveJobs\\StriveJobs' );
        $mock->shouldReceive( 'removeJobs' )
            ->once()
            ->with( ( array ) $jobId )
            ->andReturn( $result );

        $baseJob = new BaseJobClass;
        $baseJob->jobId = $jobId;
        $baseJob->striveJobs = $mock;

        $this->assertEquals( $result, $baseJob->harakiri() );
    }

    public function testSetMessage()
    {
        $message = 'Dummy_eICkdpo';

        $baseJob = new BaseJobClass;
        $baseJob->setMessage( $message );

        $this->assertEquals( $message, $baseJob->message );
    }

    public function testCallMagicMethod()
    {
        $jobId = 3432;
        $newStatus = 'cdi88d9';

        $mock = m::mock( 'StriveJobs\\StriveJobs' );
        $mock->shouldReceive( 'changeJobStatus' )
            ->once()
            ->with( 'equal', $jobId, $newStatus )
            ->andReturn( 1 );

        $baseJob = new BaseJobClass;
        $baseJob->jobId = $jobId;
        $baseJob->striveJobs = $mock;

        $this->assertEquals( true, $baseJob->setCdi88d9() );
    }

    public function testCallMagicMethodWhenFaildSaveStatus()
    {
        $jobId = 59843;
        $newStatus = 'ui7d0';

        $mock = m::mock( 'StriveJobs\\StriveJobs' );
        $mock->shouldReceive( 'changeJobStatus' )
            ->once()
            ->with( 'equal', $jobId, $newStatus )
            ->andReturn( false );

        $baseJob = new BaseJobClass;
        $baseJob->jobId = $jobId;
        $baseJob->striveJobs = $mock;

        $this->assertEquals( false, $baseJob->setUi7d0() );
    }

    public function testCallMagicMethodWithNotSetMethod()
    {
        $jobId = 646;
        $newStatus = 'lyew3';

        $mock = m::mock( 'StriveJobs\\StriveJobs' );
        $mock->shouldReceive( 'changeJobStatus' )
            ->never();

        $baseJob = new BaseJobClass;
        $baseJob->jobId = $jobId;
        $baseJob->striveJobs = $mock;

        $this->setExpectedException( 'StriveJobs\\Exceptions\\BadMethodCallException' );

        $this->assertEquals( false, $baseJob->noExistMethod() );
    }

}