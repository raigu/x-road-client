<?php

namespace Raigu\XRoad;

final class XRoadService
{
    /**
     * @var SoapEnvelopeBuilder
     */
    private $builder;
    /**
     * @var XRoadSecurityServer
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

    public static function create(string $name, string $client, XRoadSecurityServer $securityServer): self
    {
        return new self(
            SoapEnvelopeBuilder::create()
                ->withService($name)
                ->withClient($client),
            $securityServer
        );
    }

    private function __construct(SoapEnvelopeBuilder $builder, XRoadSecurityServer $securityServer)
    {
        $this->builder = $builder;
        $this->securityServer = $securityServer;
    }
}
