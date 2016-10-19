<?php

namespace Kubernetes\Client\Serializer;

use JMS\Serializer\Context;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\JsonDeserializationVisitor;
use JMS\Serializer\JsonSerializationVisitor;
use Kubernetes\Client\Model\Deployment\RollingUpdateDeployment;

class JmsSerializerRollingUpdateDeploymentHandler implements SubscribingHandlerInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribingMethods()
    {
        return [
            [
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format' => 'json',
                'type' => RollingUpdateDeployment::class,
                'method' => 'serializeRollingUpdateDeployment',
            ],
            [
                'direction' => GraphNavigator::DIRECTION_DESERIALIZATION,
                'format' => 'json',
                'type' => RollingUpdateDeployment::class,
                'method' => 'deserializeRollingUpdateDeployment',
            ],
        ];
    }

    /**
     * @param JsonSerializationVisitor $visitor
     * @param RollingUpdateDeployment $rollingUpdateDeployment
     * @param array $type
     * @param Context $context
     *
     * @return array
     */
    public function serializeRollingUpdateDeployment(JsonSerializationVisitor $visitor, RollingUpdateDeployment $rollingUpdateDeployment, array $type, Context $context)
    {
        $representation = [
            'maxUnavailable' => $this->castToStringOrInteger($rollingUpdateDeployment->getMaxUnavailable()),
            'maxSurge' => $this->castToStringOrInteger($rollingUpdateDeployment->getMaxSurge()),
        ];

        return $visitor->visitArray($representation, $type, $context);
    }

    /**
     * @param JsonDeserializationVisitor $visitor
     * @param array $data
     *
     * @return RollingUpdateDeployment
     */
    public function deserializeRollingUpdateDeployment(JsonDeserializationVisitor $visitor, array $data)
    {
        return new RollingUpdateDeployment(
            array_key_exists('maxUnavailable', $data) ? (string) $data['maxUnavailable'] : null,
            array_key_exists('maxSurge', $data) ? (string) $data['maxSurge'] : null
        );
    }

    /**
     * @param string $string
     *
     * @return int|string
     */
    private function castToStringOrInteger($string)
    {
        return $this->looksLikeAnInteger($string) ? (int) $string : (string) $string;
    }

    /**
     * @param string $string
     *
     * @return bool
     */
    private function looksLikeAnInteger($string)
    {
        return 1 === preg_match('/^\d+$/', $string);
    }
}
