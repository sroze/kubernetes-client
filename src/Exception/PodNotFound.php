<?php

namespace Kubernetes\Client\Exception;

class PodNotFound extends \Exception
{
    protected $message = 'Pod not found';
}
