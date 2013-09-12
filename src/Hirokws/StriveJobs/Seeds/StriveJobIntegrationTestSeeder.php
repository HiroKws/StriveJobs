<?php

namespace StriveJobs\Seeds;

use Illuminate\Database\Seeder;
use StriveJobs\EloquentModels\StriveJob;

class StriveJobIntegrationTestSeeder extends Seeder{

    public function run()
    {
        \Eloquent::unguard();

        StriveJob::create( array(
            'name' => 'first Job',
            'status' => 'started',
            'comment' => 'comment1',
            'argument' => 'JSON1',
        ) );
    }

}