<?php

namespace Raigu\XRoad;

use Psr\Http\Message\RequestInterface;

final class XRoadRequestBuilder
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

    public function build(): RequestInterface
    {
        return new Request;
    }

    public static function create(): self
    {
        return new self();
    }

    private function __construct()
    {
    }
}
