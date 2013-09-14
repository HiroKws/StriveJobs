<?php

namespace StriveJobs;

use StriveJobs\StriveJobsInterface;
use StriveJobs\Exceptions\InvalidArgumentException;
use StriveJobs\Exceptions\IoException;

class StriveJobs
{
    /**
     * Registered Job classes.
     *
     * @var array Instances of StriveJobs\StriveJobsInterface.
     */
    protected $jobClasses = array( );

    /**
     * Repository instance for jobs.
     *
     * @var StriveJobs\Repositories\JobsRepositoryInterface
     */
    protected $repo;

    public function __construct()
    {
        $this->repo = \App::make( 'StriveJobs\\Repositories\\JobsRepositoryInterface' );
    }

    /**
     * Register job class.
     *
     * @param Mix $jobClasses single or array of StriveJobs\StriveJobsInterface instance
     * @throws InvalidArgumentException
     */
    public function registerJobClass( $jobClasses )
    {
        $jobClasses = is_array( $jobClasses ) ? $jobClasses : array( $jobClasses );

        foreach( $jobClasses as $jobClass )
        {
            if( !$jobClass instanceof StriveJobsInterface )
            {
                throw new InvalidArgumentException( 'StriveJobs : Invalid argument.'.
                ' Only can accept StriveJobs\\StriveJobsInterface instances.' );
            }

            $this->jobClasses[$jobClass->getName()] = $jobClass;
        }
    }

    /**
     * Getter of registered job classes.
     *
     * @return array of StriveJobsInterface instance with name as key.
     */
    public function getJobClasses()
    {
        return $this->jobClasses;
    }

    /**
     * Register a job.
     *
     * @param mix $job Job class number or job name.
     * @param string $comment Comment for this job.
     * @param array $arguments Argument array for job.
     * @return mix  Return false when faild to save, otherwise job id.
     * @throws InvalidArgumentException
     */
    public function registerJob( $job, $comment = '', $arguments = array( ) )
    {
        if( ( is_numeric( $job ) and ( $job < 1 or $job > count( $this->jobClasses )) ) or
            (!is_numeric( $job ) and !key_exists( $job, $this->jobClasses )) )
        {
            throw new InvalidArgumentException( 'StriveJobs : First argument'.
            ' of registerJob method must be job number or name.' );
        }

        if( is_numeric( $job ) )
        {
            $job = key( array_slice( $this->jobClasses, $job - 1, 1, true ) );
        }

        try
        {
            $jobId = $this->repo->add( $job, $comment, $arguments );
        }
        catch( IoException $e )
        {
            return false;
        }

        return $jobId;
    }

    public function getJobs( $status = '', $limit = 0, $latestedOrder = false )
    {
        try
        {
            $jobs = $this->repo->getJobsByStatus( $status, $limit, $latestedOrder );
        }
        catch( IoException $e )
        {
            return false;
        }

        return $jobs;
    }

}