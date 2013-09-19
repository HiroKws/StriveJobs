<?php

namespace StriveJobs;

use StriveJobs\Exceptions\BadMethodCallException;

/**
 * Base class of StriveJobs' job class to extend.
 *
 * This class needed following properties.
 *
 *  striveJobs    : API instance.
 *  jobId         : Calling job's ID.
 *  status        : Calling Job's status.
 *
 * And if needed to display message,
 * put it as 'message' property.
 *
 */
class BaseJobClass
{

    /**
     * Save arguments array.
     *
     * @param array $data An argument pass to calling method in job class.
     * @return boolean False when failed.
     */
    public function putArguments( $data )
    {
        return $this->striveJobs->putArguments( $this->jobId, $data );
    }

    /**
     * Get a comment of this job.
     *
     * @return string A comment for this job.
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Save new comment.
     *
     * @param string $comment New comment.
     * @return boolean False when Failed.
     */
    public function putComment( $comment )
    {
        $result = $this->striveJobs->putComment( $this->jobId, $comment );

        if( $result !== false ) $this->comment = $result;

        return $result;
    }

    /**
     * Remove this job from a strage.
     *
     * @return boolean False when Failed.
     */
    public function removeMe()
    {
        return $this->striveJobs->removeJobs( ( array ) $this->jobId );
    }

    /**
     * Remove this job from a strage.
     * An alias of removeMe method.
     *
     * @return boolean Flase when failed.
     */
    public function killMe()
    {
        return $this->removeMe();
    }

    /**
     * Remove this job from a strage.
     * An alias of removeMe method.
     *
     * @return boolean Flase when failed.
     */
    public function harakiri()
    {
        return $this->removeMe();
    }

    /**
     * Set display message.
     *
     * @param type $message
     */
    public function setMessage( $message )
    {
        $this->message = $message;
    }

    /**
     * Handling dynamic method call.
     *
     * @param string $name
     * @param array $arguments
     * @return boolan Result of job status changed.
     */
    public function __call( $name, $arguments )
    {
        // 'setStatus' type method call handling.
        if( starts_with( $name, 'set' ) )
        {
            $newStatus = strtolower( substr( $name, 3 ) );

            $result = $this->striveJobs
                ->changeJobStatus( 'equal', $this->jobId, $newStatus );

            return $result == 1 ? true : false;
        }
        else
        {
            throw new BadMethodCallException( 'StriveJobs : '.
            'Called no existed method in'.__CLASS__.'.' );
        }
    }

}