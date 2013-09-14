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

        if( is_null( $newJob ) )
        {
            throw new IoException( 'StriveJobs : IO error to insert new job.' );
        }

        return $newJob->id;
    }

    public function isExistJobs( $ids )
    {
        $ids = ( array ) $ids;

        if( count( $ids ) == 0 ) return false;

        $jobs = $this->striveJob->whereIn( 'id', $ids )->get();

        if( count( $ids ) == count( $jobs ) ) return true;

        return false;
    }

    public function getJobsByStatus( $status = '', $limit = 0, $oldestOrder = false )
    {
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

        // pending : how catch exception from Eloquent.
        return $query->get()->toArray();
    }

    public function getJobsWithMode( $mode, $ids )
    {
        return $this->getWhereFromMode( $this->striveJob, $mode, $ids )
            ->get()->toArray();
    }

    public function changeJobStatus( $mode, $ids, $newStatus )
    {
        return $this->getWhereFromMode($this->striveJob, $mode, $ids)
            ->update( array( 'status' => $newStatus ) );
    }

    private function getWhereFromMode( $query, $mode, $ids )
    {
        switch( $mode )
        {
            case 'status' :
                $query = $query->whereIn( 'status', $ids );
                break;
            case 'equal' :
                $query = $query->whereIn( 'id', $ids );
                break;
            case 'notEqual' :
                $query = $query->whereNotIn( 'id', $ids );
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

}