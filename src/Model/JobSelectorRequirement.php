<?php

namespace Kubernetes\Client\Model;

/**
 * Class JobSelectorRequirement
 *
 * @author Samuel Roze <samuel.roze@gmail.com>
 * @author Nicolas Macherey <nicolas.macherey@gmail.com>
 */
class JobSelectorRequirement
{
    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $operator;

    /**
     * @var string[]|null
     */
    private $values;

    /**
     * @param string   $key
     * @param string   $operator
     * @param string[] $values
     */
    public function __construct($key, $operator, array $values = null)
    {
        $this->key = $key;
        $this->operator = $operator;
        $this->values = $values;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * @return string[]|null
     */
    public function getValues()
    {
        return $this->values;
    }
}
