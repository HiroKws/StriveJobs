<?php

namespace StriveJobs;

use StriveJobs\StriveJobsInterface;
use StriveJobs\Exceptions\InvalidArgumentException;
use StriveJobs\Exceptions\IoException;

/**
 * Job controle APIs
 *
 * instantiate this class in the service provider.
 */
class StriveJobs
{
    // Job select mode
    const ModStatus = 'status';

    const ModEqual = 'equal';

    const ModNotEqual = 'notEqual';

    const ModLessThan = 'lessThan';

    const ModLessThanEqual = 'lessThanEqual';

    const ModGreaterThan = 'greaterThan';

    const ModGreaterThanEqual = 'greaterThanEqual';

    /**
     * Registered Job classes.
     *
     * @var array Instances of StriveJobs\StriveJobsInterface.
     */
    protected $jobClasses = array( );

    /**
     * Last message from called class.
     *
     * @var string
     */
    protected $lastMessage = '';

    /**
     * Repository instance for jobs.
     *
     * @var StriveJobs\Repositories\JobsRepositoryInterface
     */
    protected $repo;

    /**
     * The constructor to get needed instance.
     */
    public function __construct()
    {
        // Memo: This class was instantiated in the service provider.
        // So, automatical constructor injectin still didn't work when instantiated this.
        // To defer to instantiate any classes, I don't want to pass needed object
        // from the provider.
        $this->repo = \App::make( 'StriveJobs\\Repositories\\JobsRepositoryInterface' );
    }

    /**
     * Register a job classes.
     *
     * @param Mix $jobClasses single or array of StriveJobs\StriveJobsInterface instance
     * @throws StriveJobs\Exceptions\InvalidArgumentException
     */
    public function registerJobClass( $jobClasses )
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

    /**
     * Getter for registered job classes.
     *
     * This works for the list command.
     *
     * @return array of StriveJobsInterface instance with name as key.
     */
    public function getJobClasses()
    {
        return $this->jobClasses;
    }

    /**
     * Register a job.
     *
     * @param mix $job Job class number or job name.
     * @param string $comment Comment for this job.
     * @param array $arguments Array pass into this job.
     * @return mix  Return false when faild, otherwise job id.
     * @throws StriveJobs\Exceptions\InvalidArgumentException
     */
    public function registerJob( $job, $comment = '', $arguments = array( ), $interval = 0 )
    {
        // Validate arguments.
        if( ( is_numeric( $job ) and ( $job < 1 or $job > count( $this->jobClasses )) ) or
            (!is_numeric( $job ) and !key_exists( $job, $this->jobClasses )) )
        {
            throw new InvalidArgumentException( 'StriveJobs : First argument'.
            ' of registerJob method must be job number or name.' );
        }

        // Get job class name if interger value passed as $job.
        if( is_numeric( $job ) )
        {
            // Get $job-th job class name (as key) from the array.
            $job = key( array_slice( $this->jobClasses, $job - 1, 1, true ) );
        }

        $jobId = $this->repo->add( $job, $comment, $arguments, $interval );

        return $jobId;
    }

    /**
     * Job getter.
     *
     * This is for the show command.
     *
     * @param string $status Status to get, '' for all status.
     * @param integer $limit How many jobs get, 0 for all.
     * @param boolean $latestedOrder True is later order. Older order is dafault.
     * @return mix false when faild, otherwise array of jobs.
     */
    public function getJobs( $status = '', $limit = 0, $latestedOrder = false )
    {
        $jobs = $this->repo->getJobsByStatus( $status, $limit, $latestedOrder );

        return $jobs;
    }

    /**
     * Get jobs with mode as condition.
     *
     * $mode:               $ids:
     *  'status'             Array of statuses.
     *  'equal'              Array of IDs.
     *  'notEqual'           Array of IDs.
     *  'lessThan'           Single ID.
     *  'lessThanEqual'      Single ID.
     *  'greaterThan'        Single ID.
     *  'greaterThanEqual'   Single ID.
     *
     * @param string $mode Getting mode.
     * @param mix $ids Single id or array of IDs or status.
     * @return mix False when faild, otherwise array of jobs.
     */
    public function getJobsWithMode( $mode, $ids )
    {
        if( !$this->isMode( $mode ) ) return false;

        if( $mode == 'equal' && !$this->isExistJobs( $ids ) ) return false;

        $jobs = $this->repo->getJobsWithMode( $mode, $ids );

        return $jobs;
    }

    /**
     * Getting jobs by specifed rules.
     * Automatically check next staring time.
     *
     * This is for the auto command.
     *
     * $rules:
     *  ''               Get all by descending order.
     *  'Ascending'      Get all by asscending order.
     *  Array of rules.  A rule format are :
     *                     'status:o' : Give high priorty for older jobs.
     *                     'status:l' : Give high priorty for later jobs.
     *                   Rules will be applyed with indexed.
     *
     * @param mix $rules Getting rule.
     * @return mix False when faild, otherwise array of jobs.
     */
    public function getJobsByRules( $rules )
    {
        $condition = array( );

        if( $rules == '' )
        {
            $mode = "Descending";
        }
        elseif( $rules == 'Ascending' )
        {
            $mode = "Ascending";
        }
        else
        {
            if( !is_array( $rules ) ) return false;

            $mode = "ByRules";

            foreach( $rules as $status => $sort )
            {
                switch( $sort )
                {
                    case 'o' :
                        $sortBy = 'desc';
                        break;
                    case 'l' :
                        $sortBy = 'asc';
                        break;
                    default:
                        return false;
                        break;
                }

                $condition[$status] = $sortBy;
            }
        }

        $jobs = $this->repo->getJobsByRules( $mode, $condition );

        return $jobs;
    }

    /**
     * Change job status.
     *
     * This work for the change command.
     *
     * $mode:               $ids:
     *  'status'             Array of statuses.
     *  'equal'              Array of IDs.
     *  'notEqual'           Array of IDs.
     *  'lessThan'           Single ID.
     *  'lessThanEqual'      Single ID.
     *  'greaterThan'        Single ID.
     *  'greaterThanEqual'   Single ID.
     *
     * @param string $mode Matching mode.
     * @param mix $ids Single ID or array of IDs.
     * @param string $newStatus New status.
     * @return mix False when faild, otherwise changed jobs' count.
     */
    public function changeJobStatus( $mode, $ids, $newStatus )
    {
        if( !$this->isMode( $mode ) ) return false;

        if( $mode == 'equal' && !$this->isExistJobs( $ids ) ) return false;

        $affectedCount = $this->repo->changeJobStatus( $mode, $ids, $newStatus );

        return $affectedCount;
    }

    /**
     * Execute a job.
     *
     * This is for the do commands.
     *
     * @param integer $id Job ID.
     * @return boolean execution result.
     */
    public function executeJob( $id ) //　@hiro 複数処理可能にし、エクゼキューターを独立させる
    {
        // Get job from ID.
        $job = $this->repo->getJob( $id );

        if( $job === false ) return false;

        // Check if name exists in Class names.
        if( !array_key_exists( $job['name'], $this->jobClasses ) ) return false;

        $instance = $this->jobClasses[$job['name']];
        $argument = json_decode( $job['argument'], true );
        $method = 'do'.studly_case( $job['status'] );

        $instance->jobId = $id;
        $instance->status = $job['status'];
        $instance->comment = $job['comment'];
        $instance->striveJobs = $this;

        unset( $instance->message );

        $this->lastMessage = '';

        // Call 'do'+Status method if exists.
        if( method_exists( $instance, $method ) )
        {
            $result = $instance->$method( $argument );
        }
        else
        {
            $result = $instance->doDefault( $argument );
        }

        if( isset( $instance->message ) )
        {
            $this->lastMessage = $instance->message;
        }

        // When finished job sccessfully, update next starting time.
        if( $result )
        {
            $this->repo->updateStaringTime( $id );
        }

        return $result;
    }

    /**
     * Execute jobs matched with conditions.
     *
     * This is for the auto command.
     *
     * $rules:
     *  ''               Get all by descending order.
     *  'Ascending'      Get all by asscending order.
     *  Array of rules.  A rule format are :
     *                     'status:o' : Give high priorty for older jobs.
     *                     'status:l' : Give high priorty for later jobs.
     *                   Rules will be applyed with indexed.
     *
     * @param type $rules
     * @param integer $maxExec
     * @return boolean Execution result.
     */
    public function executeByRules( $rules, $maxExec )
    {
        $jobs = $this->getJobsByRules( $rules );

        if( $jobs === false ) return false;

        $i = 1;

        foreach( $jobs as $job )
        {
            if( $i++ > $maxExec ) return true;

            if( $this->executeJob( $job['id'] ) === false ) return false;
        }

        return true;
    }

    /**
     * Remove specified jobs.
     *
     * @param array $ids Deleting IDs.
     * @return mix False when faild, otherwise count of deleted jobs.
     */
    public function removeJobs( $ids )
    {
        $ids = ( array ) $ids;

        if( empty( $ids ) ) return false;

        if( !$this->isExistJobs( $ids ) ) return false;

        $affected = $this->repo->removeJobs( $ids );

        if( $affected < 1 ) false;

        return $affected;
    }

    /**
     * Delete jobs has 'terminated' status.
     *
     * This is for the sweep command.
     *
     * @return mix False when faild, otherwise count of deleted jobs.
     */
    public function deleteTerminatedJobs()
    {
        $affected = $this->repo->deleteTerminatedJobs();

        if( $affected === false ) return false;

        return $affected;
    }

    /**
     * Truncate and reset all jobs.
     *
     * This works for the reset command.
     */
    public function truncateAllJob()
    {
        $this->repo->truncateAllJob();
    }

    /**
     * Save single job's arguments.
     *
     * @param integer $id Job ID.
     * @param array $data Arguments.
     * @return boolean Result to save.
     */
    public function putArguments( $id, $data )
    {
        if( !$this->isExistJobs( ( array ) $id ) ) return false;

        $result = $this->repo->putArguments( $id, json_encode( $data ) );

        return $result;
    }

    /**
     * Save single job's comment.
     *
     * @param integer $id Job ID.
     * @param array $comment Comment.
     * @return boolean Result to save.
     */
    public function putComment( $id, $comment )
    {
        if( !$this->isExistJobs( ( array ) $id ) ) return false;

        $result = $this->repo->putComment( $id, $comment );

        return $result;
    }

    /**
     * Check IDs is exist.
     *
     * True will be retured when all Ids existed.
     *
     * @param mix $ids Single ID or array of IDs.
     * @return boolean Flase when failed, or result of check.
     */
    public function isExistJobs( $ids ) // @hiro 失敗時と見つからない場合の切り分けは必要か？
    {
        $ids = ( array ) $ids;

        if( empty( $ids ) ) return false;

        return $this->repo->isExistJobs( $ids );
    }

    /**
     * Get last message.
     *
     * The message was saved by executed job class.
     *
     * @return string Message.
     */
    public function getMessage()
    {
        return $this->lastMessage;
    }

    /**
     * Check mode is correct.
     *
     * @param string $mode Checking mode string.
     * @return boolean True when correct.
     */
    public function isMode( $mode )
    {
        return in_array( $mode,
                         array(
            self::ModEqual,
            self::ModStatus,
            self::ModNotEqual,
            self::ModLessThan,
            self::ModLessThanEqual,
            self::ModGreaterThan,
            self::ModGreaterThanEqual
            ) );
    }

}