<?php

namespace Kubernetes\Client\Exception;

class ServiceNotFound extends ObjectNotFound
{
    protected $message = 'Service not found';
}
