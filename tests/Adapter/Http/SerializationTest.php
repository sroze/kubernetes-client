<?php

namespace Kubernetes\Client\Adapter\Http;

use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;
use Kubernetes\Client\Model\LabelSelector;
use Kubernetes\Client\Model\NetworkPolicy\NetworkPolicy;
use Kubernetes\Client\Model\NetworkPolicy\NetworkPolicyEgressRule;
use Kubernetes\Client\Model\NetworkPolicy\NetworkPolicyIngressRule;
use Kubernetes\Client\Model\NetworkPolicy\NetworkPolicyList;
use Kubernetes\Client\Model\NetworkPolicy\NetworkPolicyPeer;
use Kubernetes\Client\Model\NetworkPolicy\NetworkPolicyPort;
use Kubernetes\Client\Model\NetworkPolicy\NetworkPolicySpec;
use Kubernetes\Client\Model\ObjectMetadata;
use PHPUnit\Framework\TestCase;

class SerializationTest extends TestCase
{
    /** @var  SerializerInterface */
    private $serializer;

    public function setUp()
    {
        $this->serializer = SerializerBuilder::create()
            ->addMetadataDir(__DIR__.'/../../../src/Resources/serializer', 'Kubernetes\Client')
            ->build();
    }

    /**
     * @dataProvider objectsToSerializeProvider
     */
    public function testSerializationAndDeserialization($object)
    {
        $json = $this->serializer->serialize($object, 'json');
        $unserialized = $this->serializer->deserialize($json, get_class($object), 'json');

        $this->assertEquals($object, $unserialized);
    }

    public function objectsToSerializeProvider()
    {
        return [
            [
                new LabelSelector([
                    'app' => 'nginx-ingress'
                ]),
            ],
            [
                NetworkPolicyList::fromItems([
                    new NetworkPolicy(
                        new ObjectMetadata('my-policy'),
                        new NetworkPolicySpec(
                            [
                                new NetworkPolicyEgressRule(
                                    [
                                        new NetworkPolicyPeer(
                                            new LabelSelector([
                                                'name' => 'my-namespace',
                                            ]),
                                            new LabelSelector([
                                                'app' => 'nginx-ingress',
                                            ])
                                        )
                                    ]
                                ),
                            ],
                            [
                                new NetworkPolicyIngressRule(
                                    [
                                        new NetworkPolicyPeer(
                                            new LabelSelector([
                                                'name' => 'my-namespace',
                                            ])
                                        )
                                    ],
                                    [
                                        new NetworkPolicyPort(80, 'tcp'),
                                    ]
                                )
                            ]
                        )
                    )
                ])
            ],
        ];
    }
}
