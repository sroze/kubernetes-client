<?php

namespace Kubernetes\Client\Exception;

class NamespaceNotFound extends ObjectNotFound
{
    protected $message = 'Namespace not found';
}
