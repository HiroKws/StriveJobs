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
        return ''; // @hiro バリデーションロジック必要
    }

}