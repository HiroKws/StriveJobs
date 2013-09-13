<?php

namespace StriveJobs\Repositories;

use StriveJobs\EloquentModels\StriveJob;
use StriveJobs\Exceptions\IoException;

class StriveJobsEloquentRepository implements JobsRepositoryInterface
{

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

    public function add( $job, $comment = '', $argument = array( ) )
    {
        $newJob = $this->striveJob->create(
            array(
                'name' => $job,
                'status' => 'registered',
                'comment' => $comment,
                'argument' => json_encode( $argument )
            )
        );

        if( is_null($newJob) )
        {
            throw new IoException( 'StriveJobs : IO error to insert new job.' );
        }

        return $newJob->id;
    }

}