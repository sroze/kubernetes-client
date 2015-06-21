<?php

namespace Kubernetes\Client\Serializer;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface as JMSSerializerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class JmsSerializerAdapter implements SerializerInterface
{
    /**
     * @var JMSSerializerInterface
     */
    private $serializer;

    /**
     * @param JMSSerializerInterface $serializer
     */
    public function __construct(JMSSerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * {@inheritdoc}
     */
    public function serialize($data, $format, array $context = array())
    {
        $jmsContext = SerializationContext::create();
        $jmsContext->setGroups(array_key_exists('groups', $context) ? $context['groups'] : ['Default']);

        return $this->serializer->serialize($data, $format, $jmsContext);
    }

    /**
     * {@inheritdoc}
     */
    public function deserialize($data, $type, $format, array $context = array())
    {
        return $this->serializer->deserialize($data, $type, $format);
    }
}
