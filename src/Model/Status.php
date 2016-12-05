<?php

namespace Kubernetes\Client\Model;

class Status
{
    const UNKNOWN = 'Unknown';
    const FAILURE = 'Failure';

    /**
     * @var string
     */
    private $status;

    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $reason;

    /**
     * @var int
     */
    private $code;

    /**
     * @param string $status
     * @param string $message
     * @param string|null $reason
     * @param int|null $code
     */
    public function __construct($status, $message, $reason = null, $code = null)
    {
        $this->status = $status;
        $this->message = $message;
        $this->reason = $reason;
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }
}
