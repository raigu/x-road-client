<?php

namespace Raigu\XRoad;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\MessageInterface;
use Raigu\Test\SoapResponseStub;

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

    public function withHttpClient(ClientInterface $client): self
    {
        return new self($this->url, $client);
    }

    public static function create(string $url): self
    {
        return new self($url, new NoneClient);
    }

    public function process(MessageInterface $request): XRoadServiceResponse
    {
        return new XRoadServiceResponse(SoapResponseStub::success());
    }

    private function __construct(string $url, ClientInterface $client)
    {
        $this->url = $url;
        $this->client = $client;
    }
}
