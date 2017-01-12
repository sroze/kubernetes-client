<?php

namespace Kubernetes\Client\Exception;

use Psr\Http\Message\RequestInterface;
use Kubernetes\Client\Model\Status;

class ConnectorException extends Exception
{
    /**
     * @var Status
     */
    private $status;
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @param Status           $status
     * @param RequestInterface $request
     */
    public function __construct(Status $status, RequestInterface $request = null)
    {
        $this->status = $status;
        $this->request = $request;

        $this->message = $status->getMessage();
        $this->code = $status->getCode();
    }

    /**
     * @return Status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return RequestInterface|null
     */
    public function getRequest()
    {
        return $this->request;
    }
}
