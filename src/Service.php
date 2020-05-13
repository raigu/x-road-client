<?php

namespace Raigu\XRoad;

use Raigu\XRoad\SoapEnvelope\SoapEnvelopeBuilder;

final class Service
{
    /**
     * @var SoapEnvelopeBuilder
     */
    private $builder;
    /**
     * @var Requestable
     */
    private $securityServer;

    public function request(string $serviceRequest): string
    {
        $request = $this->builder
            ->withBody($serviceRequest)
            ->build();

        $response = $this->securityServer->request($request);

        return SoapEnvelopeAsXRoadServiceResponse::create($response)
            ->asStr();
    }

    public static function create(string $name, string $client, Requestable $securityServer): self
    {
        return new self(
            SoapEnvelopeBuilder::create()
                ->withService($name)
                ->withClient($client),
            $securityServer
        );
    }

    private function __construct(SoapEnvelopeBuilder $builder, Requestable $securityServer)
    {
        $this->builder = $builder;
        $this->securityServer = $securityServer;
    }
}
