<?php

namespace StriveJobs\Repositories;

interface JobsRepositoryInterface{

    public function getJob( $id );
    public function add( $job, $comment = '', $argument = array( ) );
    public function isExistJobs( $ids );
    public function getJobsByStatus( $status = '', $limit = 0, $oldestOrder = false );
    public function getJobsByRules( $mode, $rules );
    public function getJobsWithMode( $mode, $ids );
    public function changeJobStatus( $mode, $ids, $newStatus );
    public function removeJobs( $ids );
    public function deleteTerminatedJobs();
    public function truncateAllJob();
    public function putArguments( $id, $data );
    public function putComment( $id, $comment );

}