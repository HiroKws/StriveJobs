<?php

namespace StriveJobs\Repositories;

interface JobsRepositoryInterface{

    public function get();
    public function all();
    public function add($job, $comment, $argument = array( ));

}