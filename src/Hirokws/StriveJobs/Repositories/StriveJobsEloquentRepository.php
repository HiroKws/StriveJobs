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
        return $this->striveJob->get();
    }

    public function getJob( $id )
    {
        $job = $this->striveJob->find( $id );

        if( is_null( $job ) ) return false;

        return $job->toArray();
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

    public function getJobsByRules( $mode, $rules )
    {
        if( $mode == "Descending" )
        {
            $query = $this->striveJob
                    ->where( 'status', '!=', 'terminated' )
                    ->orderBy( 'id', 'asc' )
                    ->get()->toArray();
        }
        elseif( $mode == "Ascending" )
        {
            $query = $this->striveJob
                    ->where( 'status', '!=', 'terminated' )
                    ->orderBy( 'id', 'desc' )
                    ->get()->toArray();
        }
        elseif( $mode == "ByRules" )
        {
            $query = array( );

            foreach( $rules as $status => $sort )
            {
                $query = array_merge( $query, $this->striveJob
                        ->whereStatus( $status )->orderBy( 'id', $sort )->get()->toArray() );
            }
        }
        else
        {
            return false;
        }
        return $query;
    }

    public function getJobsWithMode( $mode, $ids )
    {
        return $this->getWhereFromMode( $this->striveJob, $mode, $ids )
                ->get()->toArray();
    }

    public function changeJobStatus( $mode, $ids, $newStatus )
    {
        return $this->getWhereFromMode( $this->striveJob, $mode, $ids )
                ->update( array( 'status' => $newStatus ) );
    }

    public function removeJobs( $ids )
    {
        return $this->striveJob->whereIn( 'id', ( array ) $ids )
                ->delete();
    }

    public function deleteTerminatedJobs()
    {
        return $this->striveJob->whereStatus( 'terminated' )
                ->delete();
    }

    public function truncateAllJob()
    {
        \DB::table( 'strive_jobs' )->truncate();
    }

    public function saveArguments( $id, $data )
    {
        return $this->striveJob->whereId( $id )
                ->update( array( 'argument' => $data ) );
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

}