<?php

use StriveJobs\Repositories\JobsEloquentRepository;

class StriveJobEloquentTest_sustend extends TestCase{

    public function setUp()
    {
        parent::setUp();

        Artisan::call( 'migrate', array( '--path' => __DIR__.'/src/migrations' ) );
        Artisan::call( 'db:seed', array( '--class' => 'StriveJobs\\Seeds\\StriveJobIntegrationTestSeeder' ) );
    }

    public function testReadAllRecode()
    {

    }

}