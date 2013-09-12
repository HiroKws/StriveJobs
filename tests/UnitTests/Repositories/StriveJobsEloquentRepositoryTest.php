<?php

use Mockery as m;
use StriveJobs\Repositories\StriveJobsEloquentRepository;

class StriveJobsEloquentRepositoryTest extends TestCase{

    /**
     * @runInSeparateProcess
     */
    public function testAll()
    {
        $mock = m::mock( 'alias:StriveJobs\\EloquentModels\\StriveJob' );
        $mock->shouldReceive( 'all' )
            ->once()
            ->andReturn( 'Now testing' );

        $repo = new StriveJobsEloquentRepository( $mock );

        $this->assertEquals( 'Now testing', $repo->get() );
    }

    /**
     * @runInSeparateProcess
     */
    public function testGet()
    {
        $mock = m::mock( 'alias:StriveJobs\\EloquentModels\\StriveJob' );
        $mock->shouldReceive( 'all' )
            ->once()
            ->andReturn( 'Now testing' );

        $repo = new StriveJobsEloquentRepository( $mock );

        $this->assertEquals( 'Now testing', $repo->get() );
    }

}