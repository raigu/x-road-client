<?php

namespace Raigu\XRoad;

use Psr\Http\Client\ClientInterface;
use Raigu\Test\Unit\SoapResponseStub;

final class XRoadSecurityServer
{
    /**
     * @var string
     */
    private $url;
    /**
     * @var ClientInterface
     */
    private $client;

    public static function create(string $url, ClientInterface $client): self
    {
        return new self($url, $client);
    }

    public function process(string $soapEnvelope): XRoadServiceResponse
    {
         $request = SoapEnvelopeAsPsr7Request::create(
           $this->url,
           $soapEnvelope
        );

        $response = $this->client->sendRequest($request);

        return Psr7ResponseAsXRoadServiceResponse::create($response);
    }

    private function __construct(string $url, ClientInterface $client)
    {
        $this->url = $url;
        $this->client = $client;
    }
}
