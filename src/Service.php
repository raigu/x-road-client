<?php

namespace Raigu\XRoad;

final class Service
{
    /**
     * @var SoapEnvelopeBuilder
     */
    private $builder;
    /**
     * @var SecurityServer
     */
    private $securityServer;

    public function request(string $serviceRequest): string
    {
        $request = $this->builder
            ->withBody($serviceRequest)
            ->build();

        $response = $this->securityServer->soapRequest($request);

        return SoapEnvelopeAsXRoadServiceResponse::create($response)
            ->asStr();
    }

    public static function create(string $name, string $client, SecurityServer $securityServer): self
    {
        return new self(
            SoapEnvelopeBuilder::create()
                ->withService($name)
                ->withClient($client),
            $securityServer
        );
    }

    private function __construct(SoapEnvelopeBuilder $builder, SecurityServer $securityServer)
    {
        $this->builder = $builder;
        $this->securityServer = $securityServer;
    }
}
