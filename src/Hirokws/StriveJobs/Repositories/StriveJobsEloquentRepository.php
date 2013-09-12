<?php

namespace StriveJobs\Repositories;

use StriveJobs\EloquentModels\StriveJob;

class StriveJobsEloquentRepository implements JobsRepositoryInterface{

    public function __construct( StriveJob $striveJob )
    {
        $this->striveJob = $striveJob;
    }

    public function all()
    {
        return $this->striveJob->all();
    }

    public function get()
    {
        return $this->striveJob->all();
    }

}