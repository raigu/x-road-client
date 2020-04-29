<?php

namespace Raigu\XRoad;

use Psr\Http\Message\MessageInterface;

final class XRoadSecurityServer
{
    public static function create(string $url): self
    {
        return new self($url);
    }

    private function __construct(string $url)
    {
    }

    public function process(MessageInterface $request): XRoadServiceResponse
    {
        return new XRoadServiceResponse;
    }
}
