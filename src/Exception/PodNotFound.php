<?php

namespace Kubernetes\Client\Exception;

class PodNotFound extends ObjectNotFound
{
    protected $message = 'Pod not found';
}
