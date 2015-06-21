<?php

namespace Kubernetes\Client\Model\Volume;

class EmptyDirVolumeSource
{
    const MEDIUM_DEFAULT = '';
    const MEDIUM_MEMORY = 'Memory';

    /**
     * @var string
     */
    private $medium;
}
