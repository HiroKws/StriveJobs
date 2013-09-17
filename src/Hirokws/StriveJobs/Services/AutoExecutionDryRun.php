<?php

namespace StriveJobs\Services;

use StriveJobs\StriveJobs;

class AutoExecutionDryRun
{

    public function __construct( StriveJobs $striveJobs )
    {
        $this->striveJobs = $striveJobs;
    }

    public function run( $parent, $rules, $maxExec )
    {
        $jobs = $this->striveJobs->getJobsByRules( $rules );

        if( $jobs === false )
        {
            $parent->error( 'Can\'t access jobs\' data.' );
            return;
        }

        $parent->info( 'There are priority list :' );

        $i = 1;

        foreach( $jobs as $job )
        {
            $parent->line(
                sprintf( '%s %d %s(%s) %s', $i <= $maxExec ? '*' : ' ', $job['id'], $job['name'], $job['status'], $job['comment'] ) );
            $i++;
        }

        $parent->info( 'Started with \'*\' item(s) will run.' );
    }

}