<?php

namespace Kubernetes\Client\Exception;

class JobNotFound extends ObjectNotFound
{
    protected $message = 'Job not found';
}
