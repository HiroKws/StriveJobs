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
            $parent->error( trans( 'StriveJobs::ChangeCommand.LogicError' ) );
            return;
        }

        $parent->info( trans( 'StriveJobs::ChangeCommand.ChangeInfo',
                              array( 'newStatus' => $newStatus ) ) );

        foreach( $jobs as $job )
        {
            $parent->line( trans( 'StriveJobs::ChangeCommand.InfoFormat',
                                  array(
                'id'        => $job['id'],
                'className' => $job['name'],
                'status'    => $job['status'],
                'comment'   => $job['comment']
            ) ) );
        }
        return;
    }

}
