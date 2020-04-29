<?php

namespace Raigu\XRoad;

final class XRoadSoapRequestBuilder
{

    public function withService(string $service): self
    {
        return new self();
    }

    public function withClient(string $client): self
    {
        return new self();
    }

    public function withBody(string $body): self
    {
        return new self();
    }

    public function build(): \Psr\Http\Message\MessageInterface
    {
        return new InMemoryPsr7Message;
    }

    public static function create(): self
    {
        return new self();
    }

    private function __construct()
    {
    }
}
