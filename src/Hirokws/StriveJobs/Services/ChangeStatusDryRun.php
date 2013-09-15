<?php

namespace StriveJobs\Services;

use StriveJobs\StriveJobs;

class ChangeStatusDryRun
{

    public function __construct( StriveJobs $striveJobs )
    {
        $this->striveJobs = $striveJobs;
    }

    public function change( $parent, $mode, $ids, $newStatus )
    {
        $jobs = $this->striveJobs->getJobsWithMode( $mode, $ids );

        if( $jobs === false )
        {
            $parent->error( 'Logical error happened in change status command.' );
            return;
        }

        $parent->info( "Following job's status will be change to {$newStatus} :" );

        foreach( $jobs as $job )
        {
            $parent->line( sprintf( "%d %s(%s) \"%s\"", $job['id'], $job['name'], $job['status'], $job['comment'] ) );
        }
        return;
    }

}
