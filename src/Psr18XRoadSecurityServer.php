<?php

namespace Raigu\XRoad;

use Exception;
use Psr\Http\Client\ClientInterface;

final class Psr18XRoadSecurityServer implements XRoadSecurityServer
{
    /**
     * @var string
     */
    private $url;
    /**
     * @var ClientInterface
     */
    private $client;

    public function process(string $soapEnvelope): XRoadServiceResponse
    {
        $factory = Psr7RequestFactory::default();
        $request = $factory->fromSoapEnvelope(
            $this->url,
            $soapEnvelope
        );

        $response = $this->client->sendRequest($request);

        $validation = SoapFaultExcluded::create($response);
        $validation->validate();

        $code = $response->getStatusCode();
        if ($code < 200 or 299 < $code) {
            throw new Exception(
                'Did not return proper response from X-Road Security server. ' .
                'Expecting HTTP Status code 2xx, actual ' . $code
            );
        }

        return Psr7ResponseAsXRoadServiceResponse::create($response);
    }

    public static function create(string $url, ClientInterface $client): self
    {
        return new self($url, $client);
    }

    private function __construct(string $url, ClientInterface $client)
    {
        $this->url = $url;
        $this->client = $client;
    }
}
