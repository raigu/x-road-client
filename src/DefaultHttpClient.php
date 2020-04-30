<?php

namespace Raigu\XRoad;

use Nyholm\HttpClient\Client;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * I am a default PSR-8 compatible client for this package
 */
final class DefaultHttpClient implements ClientInterface
{
    /**
     * @var
     */
    private $client;

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        return $this->client->sendRequest($request);
    }

    public static function create(): ClientInterface
    {
        return new self(new Client);
    }

    private function __construct(Client $client)
    {
        $this->client = $client;
    }
}
