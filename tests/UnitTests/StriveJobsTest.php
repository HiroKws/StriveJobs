<?php

use Mockery as m;
use StriveJobs\StriveJobs;

class StriveJobsTest extends TestCase
{

    public function testRegisterAndGetter()
    {
        $mock1 = m::mock( 'stdClass, StriveJobs\\StriveJobsInterface' );
        $mock1->shouldReceive( 'getName' )
            ->once()
            ->andReturn( 'Name1' );

        $mock2 = m::mock( 'stdClass, StriveJobs\\StriveJobsInterface' );
        $mock2->shouldReceive( 'getName' )
            ->once()
            ->andReturn( 'Name2' );

        $striveJobs = new StriveJobs;
        $striveJobs->registerJobClass( array( $mock1, $mock2 ) );

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

        $striveJobs = new StriveJobs;
        $striveJobs->registerJobClass( $mock1 );

        $excepted = array(
            'Name1' => $mock1
        );
        $this->assertEquals( $excepted, $striveJobs->getJobClasses() );
    }

    public function testRegisterJob()
    {
        $repoMock = m::mock( 'StriveJobs\\Repositories\\JobsRepositoryInterface' );
        $repoMock->shouldReceive( 'add' )
            ->with( 'Job1', 'Comment', array( 'time' => '2' ) )
            ->once()
            ->andReturn( 1 );

        App::instance( 'StriveJobs\\Repositories\\JobsRepositoryInterface', $repoMock );

        $mock1 = m::mock( 'stdClass, StriveJobs\\StriveJobsInterface' );
        $mock1->shouldReceive( 'getName' )
            ->once()
            ->andReturn( 'Job1' );


        $striveJobs = new StriveJobs;
        $striveJobs->registerJobClass( $mock1 );

        $this->assertEquals( 1, $striveJobs
                ->registerJob( 'Job1', 'Comment', array( 'time' => '2' ) ) );
    }

    public function testRegisterJobWithMatchedJobNumber()
    {
        $repoMock = m::mock( 'StriveJobs\\Repositories\\JobsRepositoryInterface' );
        $repoMock->shouldReceive( 'add' )
            ->with( 'Job1', 'Comment', array( 'time' => '2' ) )
            ->once()
            ->andReturn( 1 );

        App::instance( 'StriveJobs\\Repositories\\JobsRepositoryInterface', $repoMock );

        $mock1 = m::mock( 'stdClass, StriveJobs\\StriveJobsInterface' );
        $mock1->shouldReceive( 'getName' )
            ->once()
            ->andReturn( 'Job1' );


        $striveJobs = new StriveJobs;
        $striveJobs->registerJobClass( $mock1 );

        $this->assertEquals( 1, $striveJobs
                ->registerJob( '1', 'Comment', array( 'time' => '2' ) ) );
    }

    public function testRegisterJobWithUnmatchedJobName()
    {
        $repoMock = m::mock( 'StriveJobs\\Repositories\\JobsRepositoryInterface' );
        $repoMock->shouldReceive( 'add' )
            ->with( 'Job1', 'Comment', array( 'time' => '2' ) )
            ->never()
            ->andReturn( 1 );

        App::instance( 'StriveJobs\\Repositories\\JobsRepositoryInterface', $repoMock );

        $mock1 = m::mock( 'stdClass, StriveJobs\\StriveJobsInterface' );
        $mock1->shouldReceive( 'getName' )
            ->once()
            ->andReturn( 'Job1' );


        $striveJobs = new StriveJobs;
        $striveJobs->registerJobClass( $mock1 );

        $this->setExpectedException( 'StriveJobs\\Exceptions\\InvalidArgumentException' );

        $striveJobs->registerJob( 'No Name', 'Comment', array( 'time' => '2' ) );
    }

    public function testRegisterJobWithTooBigJobNumber()
    {
        $repoMock = m::mock( 'StriveJobs\\Repositories\\JobsRepositoryInterface' );
        $repoMock->shouldReceive( 'add' )
            ->with( 'Job1', 'Comment', array( 'time' => '2' ) )
            ->never()
            ->andReturn( 1 );

        App::instance( 'StriveJobs\\Repositories\\JobsRepositoryInterface', $repoMock );

        $mock1 = m::mock( 'stdClass, StriveJobs\\StriveJobsInterface' );
        $mock1->shouldReceive( 'getName' )
            ->once()
            ->andReturn( 'Job1' );


        $striveJobs = new StriveJobs;
        $striveJobs->registerJobClass( $mock1 );

        $this->setExpectedException( 'StriveJobs\\Exceptions\\InvalidArgumentException' );

        $striveJobs->registerJob( '2', 'Comment', array( 'time' => '2' ) );
    }

    public function testRegisterJobWithTooFewJobNumber()
    {
        $repoMock = m::mock( 'StriveJobs\\Repositories\\JobsRepositoryInterface' );
        $repoMock->shouldReceive( 'add' )
            ->with( 'Job1', 'Comment', array( 'time' => '2' ) )
            ->never()
            ->andReturn( 1 );

        App::instance( 'StriveJobs\\Repositories\\JobsRepositoryInterface', $repoMock );

        $mock1 = m::mock( 'stdClass, StriveJobs\\StriveJobsInterface' );
        $mock1->shouldReceive( 'getName' )
            ->once()
            ->andReturn( 'Job1' );


        $striveJobs = new StriveJobs;
        $striveJobs->registerJobClass( $mock1 );

        $this->setExpectedException( 'StriveJobs\\Exceptions\\InvalidArgumentException' );

        $striveJobs->registerJob( '0', 'Comment', array( 'time' => '2' ) );
    }

    public function testRegisterJobWhenIoErrorHappen()
    {
        $repoMock = m::mock( 'StriveJobs\\Repositories\\JobsRepositoryInterface' );
        $repoMock->shouldReceive( 'add' )
            ->with( 'Job1', 'Comment', array( 'time' => '2' ) )
            ->once()
            ->andThrow( 'StriveJobs\\Exceptions\\IoException' );

        App::instance( 'StriveJobs\\Repositories\\JobsRepositoryInterface', $repoMock );

        $mock1 = m::mock( 'stdClass, StriveJobs\\StriveJobsInterface' );
        $mock1->shouldReceive( 'getName' )
            ->once()
            ->andReturn( 'Job1' );

        $striveJobs = new StriveJobs;
        $striveJobs->registerJobClass( $mock1 );

        $this->assertFalse( $striveJobs
                ->registerJob( '1', 'Comment', array( 'time' => '2' ) ) );
    }

}