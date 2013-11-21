<?php

namespace StriveJobs\Services;

use StriveJobs\StriveJobs;

class ListJobInfo{

    public function __construct( StriveJobs $striveJobs )
    {
        $this->striveJobs = $striveJobs;
    }

    public function get()
    {
        $classInfo = array( );
        $number = 1;

        foreach( $this->striveJobs->getJobClasses() as $class )
        {
            $info = new \stdClass;

            $info->number = $number++;
            $info->name = $class->getName();
            $info->description = $class->getDescription();

            $classInfo[] = $info;
        }

        return $classInfo;
    }

}