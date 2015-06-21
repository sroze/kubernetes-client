<?php

namespace Kubernetes\Client\Exception;

class ReplicationControllerNotFound extends ObjectNotFound
{
    protected $message = 'Replication controller not found';
}
