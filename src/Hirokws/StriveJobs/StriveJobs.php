<?php

namespace StriveJobs;

use StriveJobs\StriveJobsInterface;
use StriveJobs\Exceptions\InvalidArgumentException;

class StriveJobs{

    protected $jobClasses = array( );

    public function register( $jobClasses )
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

    public function getJobClasses()
    {
        return $this->jobClasses;
    }

}