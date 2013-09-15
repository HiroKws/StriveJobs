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
     * Last message from called class.
     *
     * @var string
     */
    protected $lastMessage = '';

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

    public function getJobsWithMode( $mode, $ids )
    {
        if( !$this->isMode( $mode ) ) return false;

        if( $mode == 'equal' && !$this->isExistJobs( $ids ) ) return false;

        try
        {
            $jobs = $this->repo->getJobsWithMode( $mode, $ids );
        }
        catch( IoException $e )
        {
            return false;
        }

        return $jobs;
    }

    public function changeJobStatus( $mode, $ids, $newStatus )
    {
        if( !$this->isMode( $mode ) ) return false;

        if( $mode == 'equal' && !$this->isExistJobs( $ids ) ) return false;

        try
        {
            $affectedCount = $this->repo->changeJobStatus( $mode, $ids, $newStatus );
        }
        catch( IoException $e )
        {
            return false;
        }

        return $affectedCount;
    }

    public function executeJob( $id )
    {
        // Get job from ID.
        $job = $this->repo->getJob( $id );

        if( $job === false ) return false;

        // Check name is exist in Class names.
        if( !array_key_exists( $job['name'], $this->jobClasses ) ) return false;

        $instance = $this->jobClasses[$job['name']];
        $argument = json_decode( $job['argument'], true );
        $method = 'do'.studly_case( $job['status'] );

        $instance->jobId = $id;
        $instance->status = $job['status'];
        $instance->striveJobs = $this;

        unset( $instance->message );

        $this->lastMessage = '';

        // At first, try to call 'do'+Status method.

        if( method_exists( $instance, $method ) )
        {
            $result = $instance->$method( $argument );

            if( isset( $instance->message ) )
            {
                $this->lastMessage = $instance->message;
            }

            return $result;
        }

        // If not exist 'do'+Status method in job class,
        // call default method.

        $result = $instance->doDefault( $argument );

        if( isset( $instance->message ) )
        {
            $this->lastMessage = $instance->message;
        }

        return $result;
    }

    public function removeJobs( $ids )
    {
        $ids = ( array ) $ids;

        if( empty( $ids ) ) return false;

        if( !$this->isExistJobs( $ids ) ) return false;

        $affected = $this->repo->removeJobs( $ids );

        if( $affected < 1 ) false;

        return $affected;
    }

    public function deleteTerminatedJobs()
    {
        $affected = $this->repo->deleteTerminatedJobs();

        if( $affected === false ) return false;

        return $affected;
    }

    public function truncateAllJob()
    {
        $this->repo->truncateAllJob();
    }


    public function saveArguments( $id, $data )
    {
        if( !$this->isExistJobs( ( array ) $id ) ) return false;

        $result = $this->repo->saveArguments( $id, json_encode( $data ) );

        return $result;
    }

    public function isExistJobs( $ids )
    {
        $ids = ( array ) $ids;

        if( empty( $ids ) ) return false;

        try
        {
            return $this->repo->isExistJobs( $ids );
        }
        catch( IoException $e )
        {
            return false;
        }
    }

    public function getMessage()
    {
        return $this->lastMessage;
    }

    public function isMode( $mode )
    {
        return in_array( $mode, array(
            'status',
            'equal',
            'notEqual',
            'lessThan',
            'lessThanEqual',
            'greaterThan',
            'greaterThanEqual'
            ) );
    }

}