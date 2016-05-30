<?php

namespace Kubernetes\Client\Model;

class Probe
{
    /**
     * @var ExecAction
     */
    private $exec;

    /**
     * @var HttpGetAction
     */
    private $httpGet;

    /**
     * @var TcpSocketAction
     */
    private $tcpSocket;

    /**
     * @var int
     */
    private $initialDelaySeconds;

    /**
     * @var int
     */
    private $timeoutSeconds;

    /**
     * @var int
     */
    private $periodSeconds;

    /**
     * @var int
     */
    private $successThreshold;

    /**
     * @var int
     */
    private $failureThreshold;

    /**
     * @param ExecAction      $exec
     * @param HttpGetAction   $httpGet
     * @param TcpSocketAction $tcpSocket
     * @param int             $initialDelaySeconds
     * @param int             $timeoutSeconds
     * @param int             $periodSeconds
     * @param int             $successThreshold
     * @param int             $failureThreshold
     */
    public function __construct(ExecAction $exec = null, HttpGetAction $httpGet = null, TcpSocketAction $tcpSocket = null, $initialDelaySeconds = null, $timeoutSeconds = null, $periodSeconds = null, $successThreshold = null, $failureThreshold = null)
    {
        $this->exec = $exec;
        $this->httpGet = $httpGet;
        $this->tcpSocket = $tcpSocket;
        $this->initialDelaySeconds = $initialDelaySeconds;
        $this->timeoutSeconds = $timeoutSeconds;
        $this->periodSeconds = $periodSeconds;
        $this->successThreshold = $successThreshold;
        $this->failureThreshold = $failureThreshold;
    }

    /**
     * @return ExecAction
     */
    public function getExec()
    {
        return $this->exec;
    }

    /**
     * @return HttpGetAction
     */
    public function getHttpGet()
    {
        return $this->httpGet;
    }

    /**
     * @return TcpSocketAction
     */
    public function getTcpSocket()
    {
        return $this->tcpSocket;
    }

    /**
     * @return int
     */
    public function getInitialDelaySeconds()
    {
        return $this->initialDelaySeconds;
    }

    /**
     * @return int
     */
    public function getTimeoutSeconds()
    {
        return $this->timeoutSeconds;
    }

    /**
     * @return int
     */
    public function getPeriodSeconds()
    {
        return $this->periodSeconds;
    }

    /**
     * @return int
     */
    public function getSuccessThreshold()
    {
        return $this->successThreshold;
    }

    /**
     * @return int
     */
    public function getFailureThreshold()
    {
        return $this->failureThreshold;
    }
}
