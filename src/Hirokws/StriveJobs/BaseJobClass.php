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

    public function saveArguments( $data )
    {
        return $this->striveJobs->saveArguments( $this->jobId, $data );
    }

    public function removeMe()
    {
        return $this->striveJobs->removeJobs( ( array ) $this->jobId );
    }

    public function killMe()
    {
        return $this->removeMe();
    }

    public function harakiri()
    {
        return $this->removeMe();
    }

    public function setMessage($message)
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