<?php

namespace Raigu\XRoad;

use Exception;
use Psr\Http\Client\ClientInterface;

/**
 * I am a default implementation for security server
 */
final class DefaultSecurityServer implements Requestable
{
    /**
     * @var string
     */
    private $url;
    /**
     * @var ClientInterface
     */
    private $client;

    public function request(string $request): string
    {
        $factory = Psr7RequestFactory::default();
        $request = $factory->fromSoapEnvelope(
            $this->url,
            $request
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

        return Psr7ResponseAsXRoadServiceResponse::create($response)->asStr();
    }

    public static function overPsr18Client(string $url, ClientInterface $client)
    {
        return new self($url, $client);
    }

    public static function create(string $url): self
    {
        return new self($url, new CurlPsr18Adapter);
    }

    private function __construct(string $url, ClientInterface $client)
    {
        $this->url = $url;
        $this->client = $client;
    }
}
