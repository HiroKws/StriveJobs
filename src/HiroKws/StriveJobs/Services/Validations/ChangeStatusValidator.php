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
        // Omited to check required argument,
        // because it will done Symfony Input class.
        // Check duplicated specifying with matching options.
        $ids = array( );
        $existId = 0;
        $conditionCount = 0;

        if( !empty( $args['status'] ) )
        {
            $conditionCount++;
        }
        if( !empty( $args['id'] ) )
        {
            $existId = 1;
            $conditionCount++;
            $ids = $args['id'];
        }
        if( !empty( $args['notId'] ) )
        {
            $conditionCount++;
            $ids = $args['notId'];
        }
        if( !empty( $args['lessThan'] ) )
        {
            $conditionCount++;
            $ids[] = $args['lessThan'];
        }
        if( !empty( $args['lessThanEqual'] ) )
        {
            $conditionCount++;
            $ids[] = $args['lessThanEqual'];
        }
        if( !empty( $args['greaterThan'] ) )
        {
            $conditionCount++;
            $ids[] = $args['greaterThan'];
        }
        if( !empty( $args['greaterThanEqual'] ) )
        {
            $conditionCount++;
            $ids[] = $args['greaterThanEqual'];
        }

        if( $conditionCount != 1 )
        {
            return \Lang::get( 'StriveJobs::ChangeCommand.NotOneOfMode' );
        }

        if( $existId == 1 )
        {
            // Check specified ID is wheather existed.
            if( !$this->striveJobs->isExistJobs( $ids ) )
            {
                return \Lang::get( 'StriveJobs::ChangeCommand.IdNotFound' );
            }
        }

        return '';
    }

}