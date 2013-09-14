<?php

namespace StriveJobs\Services\Validations;

use StriveJobs\StriveJobs;

class ChangeStatusValidator
{
    protected $striveJobs;

    public function __construct( StriveJobs $striveJobs )
    {
        $this->striveJobs = $striveJobs;
    }

    /**
     * Validate arguments.
     *
     * @param type $args
     * @return string Error message. Empty string when passed.
     */
    public function validate( $args )
    {
        // Check required argument.
        if( !isset( $args['newStatus'] ) )
        {
            return 'New job status is require.';
        }

        // Check duplicate specifying options.
        $ids = array( );
        $existStatus = 0;
        $existId = 0;
        $existNotEqual = 0;
        $existLessThan = 0;
        $existLessThanEqual = 0;
        $existGreaterThan = 0;
        $existGreaterThanEqual = 0;

        if( !empty( $args['status'] ) )
        {
            $existStatus = 1;
        }
        if( !empty( $args['id'] ) )
        {
            $existId = 1;
            $ids = $args['id'];
        }
        if( !empty( $args['notId'] ) )
        {
            $existNotEqual = 1;
            $ids = $args['notId'];
        }
        if( !is_null( $args['lessThan'] ) )
        {
            $existLessThan = 1;
            $ids[] = $args['lessThan'];
        }
        if( !is_null( $args['lessThanEqual'] ) )
        {
            $existLessThanEqual = 1;
            $ids[] = $args['lessThanEqual'];
        }
        if( !is_null( $args['greaterThan'] ) )
        {
            $existGreaterThan = 1;
            $ids[] = $args['greaterThan'];
        }
        if( !is_null( $args['greaterThanEqual'] ) )
        {
            $existGreaterThanEqual = 1;
            $ids[] = $args['greaterThanEqual'];
        }

        if( ($existStatus + $existId + $existNotEqual + $existLessThan + $existLessThanEqual + $existGreaterThan + $existGreaterThanEqual) != 1 )
        {
            return 'Please specify targets by using one option. (--id and --notEqual can be used multiple.)';
        }

        if( $existId == 1 )
        {
            // Check specified ID is wheather existed.
            if( !$this->striveJobs->isExistJobs( $ids ) )
            {
                return 'ID not found in registered jobs.';
            }
        }

        return '';
    }

}