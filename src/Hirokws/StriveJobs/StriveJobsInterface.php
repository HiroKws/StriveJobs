<?php

namespace StriveJobs;

interface StriveJobsInterface
{
    public function getName() ;
    public function getDescription();
    public function start();
    public function resume();
}