<?php

namespace Kubernetes\Client\Model;

class ExecAction
{
    /**
     * This is an array of string.
     *
     * @var array
     */
    private $command;

    /**
     * @param array $command
     */
    public function __construct(array $command)
    {
        $this->command = $command;
    }

    /**
     * @return array
     */
    public function getCommand()
    {
        return $this->command;
    }
}
