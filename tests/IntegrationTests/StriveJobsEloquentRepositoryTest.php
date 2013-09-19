<?php

use Mockery as m;
use StriveJobs\Repositories\StriveJobsEloquentRepository;

class StriveJobsEloquentRepositoryTest extends TestCase
{
// ユニットテストは無意味　テストDBを使用した結合をすべき　
    /**
     * @runInSeparateProcess
     */
    public function testGetJob()
    {
        $jobId = 838;

        $mock = m::mock( 'StriveJobs\\EloquentModels\\StriveJob[find]' );
        $mock->shouldReceive( 'find' )
            ->once()
            ->with($jobId)
            ->andReturn( 20 );
        $repo = new StriveJobsEloquentRepository( $mock );

        $this->assertEquals( 'Now testing', $repo->getJob($jobId) );
    }
//    public function testGet()
//    {
//        $mock = m::mock( 'alias:StriveJobs\\EloquentModels\\StriveJob' );
//        $mock->shouldReceive( 'all' )
//            ->once()
//            ->andReturn( 'Now testing' );
//
//        $repo = new StriveJobsEloquentRepository( $mock );
//
//        $this->assertEquals( 'Now testing', $repo->get() );
//    }

}