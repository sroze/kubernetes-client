<?php

namespace Kubernetes\Client\Exception;

class CronJobNotFound extends ObjectNotFound
{
    protected $message = 'CronJob not found';
}
