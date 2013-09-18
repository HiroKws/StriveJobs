<?php

namespace StriveJobs\Services\Validations;

use StriveJobs\StriveJobs;

class AutoExecutionValidator
{
    protected $striveJobs;

    public function __construct( StriveJobs $striveJobs )
    {
        $this->striveJobs = $striveJobs;
    }

    public function validate( $args )
    {
        $setLatest = 0;
        $setRule =0;
        $matched = array();

        if( $args['latest'] )
        {
            $setLatest = 1;
        }

        if( !empty( $args['rule'] ) )
        {
            $setRule = 1;

            foreach( $args['rule'] as $rule )
            {
                if( !preg_match( '/\w:(\w)/', $rule, $matched ) )
                {
                    return 'All rules must be \'status:l\' or \'status:o\' format.';
                }

                if( $matched[1] != 'o' and $matched[1] != 'l' )
                {
                    return 'An priority order in each rule must be `o`(older top) or `l`(leater top).';
                }
            }
        }

        if( $setLatest + $setRule > 1 )
        {
            return 'Can\t use --latest and --rule both at once.';
        }

        if( !is_numeric( $args['execute'] ) )
        {
            return 'Please set integer value to --execute.';
        }
        return '';
    }

}