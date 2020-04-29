<?php

namespace Raigu\XRoad;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class NoneClient implements ClientInterface
{
    public function sendRequest(RequestInterface $request): ResponseInterface
    {

    }
}
