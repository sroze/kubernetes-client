<?php

namespace Kubernetes\Client\Model;

class ObjectMetadata
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $uid;

    /**
     * @var KeyValueObjectList
     */
    private $labelList;

    /**
     * @var KeyValueObjectList
     */
    private $annotationList;

    /**
     * @param string             $name
     * @param KeyValueObjectList $labelList
     * @param KeyValueObjectList $annotationList
     */
    public function __construct($name, KeyValueObjectList $labelList = null, KeyValueObjectList $annotationList = null)
    {
        $this->name = $name;
        $this->labelList = $labelList;
        $this->annotationList = $annotationList;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return KeyValueObjectList
     */
    public function getLabelList()
    {
        if (null === $this->labelList) {
            $this->labelList = new KeyValueObjectList();
        }

        return $this->labelList;
    }

    /**
     * @return KeyValueObjectList
     */
    public function getAnnotationList()
    {
        if (null === $this->annotationList) {
            $this->annotationList = new KeyValueObjectList();
        }

        return $this->annotationList;
    }

    public function getLabelsAsAssociativeArray()
    {
        return $this->getLabelList()->toAssociativeArray();
    }

    public function setLabelsFromAssociativeArray(array $labels)
    {
        $this->labelList = KeyValueObjectList::fromAssociativeArray($labels, Label::class);
    }

    public function getAnnotationsAsAssociativeArray()
    {
        return $this->getAnnotationList()->toAssociativeArray();
    }

    public function setAnnotationsFromAssociativeArray(array $annotations)
    {
        $this->annotationList = KeyValueObjectList::fromAssociativeArray($annotations, Annotation::class);
    }

    public function __clone()
    {
        $this->labelList = clone $this->labelList;
        $this->annotationList = clone $this->annotationList;
    }
}
