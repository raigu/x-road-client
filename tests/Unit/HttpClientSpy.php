<?php

namespace Raigu\Test\Unit;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class HttpClientSpy implements ClientInterface
{

    /**
     * @var ClientInterface
     */
    private $client;
    /**
     * @var bool
     */
    private $called;

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $this->called = true;

        return $this->client->sendRequest($request);
    }

    public function called(): bool
    {
        return $this->called;
    }

    public static function wrap(ClientInterface $client): self
    {
        return new self($client);
    }

    private function __construct(ClientInterface $client)
    {
        $this->client = $client;
        $this->called = false;
    }
}
