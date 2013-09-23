<?php

namespace StriveJobs\Repositories;

use StriveJobs\EloquentModels\StriveJob;
use StriveJobs\Exceptions\IoException;

class StriveJobsEloquentRepository implements JobsRepositoryInterface
{
    protected $striveJob;

    public function __construct( StriveJob $striveJob )
    {
        $this->striveJob = $striveJob;
    }

    public function getJob( $id )
    {
        try
        {
            $job = $this->striveJob->find( $id );
        }
        catch( \Exception $e )
        {
            throw new IoException( 'StriveJobs : Can\'t access jobs.' );
        }

        if( is_null( $job ) ) return false;

        return $job->toArray();
    }

    public function getJobsByStatus( $status = '', $limit = 0, $oldestOrder = false )
    {
        // Build up a query.
        $query = $this->striveJob;

        if( $status != '' )
        {
            $query = $query->where( 'status', $status );
        }

        if( $limit != 0 )
        {
            $query = $query->take( $limit );
        }

        if( !$oldestOrder )
        {
            $query = $query->orderBy( 'id', 'desc' );
        }

        // Get jobs.
        try
        {
            $jobs = $query->get()->toArray();
        }
        catch( \Exception $e )
        {
            throw new IoException( 'StriveJobs : Faild to get jobs.' );
        }

        // Empty $jobs is acceptable, because there are no jobs matched with conditions.
        return $jobs;
    }

    public function getJobsByRules( $mode, $rules )
    {
        $now = date(\DateTime::ATOM);

        if( $mode == "Descending" )
        {
            try
            {// @hiro ascとdescを見直す
                $jobs = $this->striveJob
                        ->where( 'status', '!=', 'terminated' )
                        ->where( 'starting_at', '<=', $now )
                        ->orderBy( 'id', 'asc' )
                        ->get()->toArray();
            }
            catch( \Exception $e )
            {
                throw new IoException( 'StriveJobs : Can\'t get jobs.' );
            }
        }
        elseif( $mode == "Ascending" )
        {
            try
            {
                $jobs = $this->striveJob
                        ->where( 'status', '!=', 'terminated' )
                        ->where( 'starting_at', '<=', $now )
                        ->orderBy( 'id', 'desc' )
                        ->get()->toArray();
            }
            catch( \Exception $e )
            {
                throw new IoException( 'StriveJobs : Can\'t get jobs.' );
            }
        }
        elseif( $mode == "ByRules" )
        {
            $jobs = array( );

            foreach( $rules as $status => $sort )
            {
                try
                {
                    $jobs = array_merge( $jobs,
                                         $this->striveJob
                            ->where( 'starting_at', '<=', $now )
                            ->whereStatus( $status )
                            ->orderBy( 'id', $sort )
                            ->get()->toArray() );
                }
                catch( \Exception $e )
                {
                    throw new IoException( 'StriveJobs : Can\'t get jobs.' );
                }
            }
        }
        else
        {
            return false;
        }

        return $jobs;
    }

    public function getJobsWithMode( $mode, $ids )
    {
        try
        {
            $jobs = $this
                    ->getWhereFromMode( $this->striveJob, $mode, $ids )
                    ->get()->toArray();
        }
        catch( \Exception $e )
        {
            throw new IoException( 'StriveJobs : Can\'t get jobs.' );
        }

        return $jobs;
    }

    private function getWhereFromMode( $query, $mode, $ids )
    {
        switch( $mode )
        {
            case 'status' :
                $query = $query->whereIn( 'status', ( array ) $ids );
                break;
            case 'equal' :
                $query = $query->whereIn( 'id', ( array ) $ids );
                break;
            case 'notEqual' :
                $query = $query->whereNotIn( 'id', ( array ) $ids );
                break;
            case 'lessThan' :
                $query = $query->where( 'id', '<', $ids );
                break;
            case 'lessThanEqual' :
                $query = $query->where( 'id', '<=', $ids );
                break;
            case 'greaterThan':
                $query = $query->where( 'id', '>', $ids );
                break;
            case 'greaterThanEqual' :
                $query = $query->where( 'id', '>=', $ids );
        }

        return $query;
    }

    public function add( $job, $comment = '', $argument = array( ), $interval = 0 )
    {
        try
        {
            $newJob = $this->striveJob->create(
                array(
                    'name'        => $job,
                    'status'      => 'registered',
                    'comment'     => $comment,
                    'argument'    => json_encode( $argument ),
                    'interval'    => $interval,
                    'starting_at' => date( \DateTime::ATOM, time() + $interval * 60 ),
                )
            );
        }
        catch( \Exception $e )
        {
            throw new IoException( 'StriveJobs : Exception happened when insert new job.' );
        }


        if( is_null( $newJob ) )
        {
            throw new IoException( 'StriveJobs : Can\'t create new job.' );
        }

        return $newJob->id;
    }

    public function changeJobStatus( $mode, $ids, $newStatus )
    {
        try
        {
            $result = $this->getWhereFromMode( $this->striveJob, $mode, $ids )
                ->update( array( 'status' => $newStatus ) );
        }
        catch( \Exception $e )
        {
            throw new IoException( 'StriveJobs : Can\'t change job status.' );
        }

        return $result;
    }

    public function updateStaringTime( $id )
    {
        try
        {
            $job = $this->striveJob->find( $id );

            // When call removed job, return null.
            if (is_null($job)) return true;

            $job->starting_at = date( \DateTime::ATOM,
                                      strtotime( $job->starting_at ) + $job->interval * 60 );
            $result = $job->save();
        }
        catch( \Exception $e )
        {
            throw new IoException( 'StriveJobs : Can\'t update starting time.' );
        }

        return $result;
    }

    public function putArguments( $id, $data )
    {
        try
        {
            $result = $this->striveJob->whereId( $id )
                ->update( array( 'argument' => $data ) );
        }
        catch( \Exception $e )
        {
            throw new IoException( 'StriveJobs : Can\'t create new job.' );
        }

        return $result;
    }

    public function putComment( $id, $comment )
    {
        try
        {
            $result = $this->striveJob
                ->whereId( $id )
                ->update( array( 'comment' => $comment ) );
        }
        catch( \Exception $e )
        {
            throw new IoException( 'StriveJobs : Can\'t create new job.' );
        }
        return $result;
    }

    public function removeJobs( $ids )
    {
        try
        {
            $result = $this->striveJob
                ->whereIn( 'id', ( array ) $ids )
                ->delete();
        }
        catch( \Exception $e )
        {
            throw new IoException( 'StriveJobs : Can\'t create new job.' );
        }

        return $result;
    }

    public function deleteTerminatedJobs()
    {
        try
        {
            $result = $this->striveJob
                ->whereStatus( 'terminated' )
                ->delete();
        }
        catch( \Exception $e )
        {
            throw new IoException( 'StriveJobs : Can\'t create new job.' );
        }
        return $result;
    }

    public function truncateAllJob()
    {
        try
        {
            \DB::table( 'strive_jobs' )->truncate();
        }
        catch( \Exception $e )
        {
            throw new IoException( 'StriveJobs : Can\'t create new job.' );
        }
    }

    public function isExistJobs( $ids )
    {
        $ids = ( array ) $ids;

        if( count( $ids ) == 0 ) return false;

        try
        {
            $jobs = $this->striveJob->whereIn( 'id', $ids )->get();
        }
        catch( \Exception $e )
        {
            throw new IoException( 'StriveJobs : Can\'t access jobs.' );
        }

        if( count( $ids ) == count( $jobs ) ) return true;

        return false;
    }

}