<?php

namespace Raigu\Test\Unit;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class HttpClientStub implements ClientInterface
{
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        return SoapResponseStub::success();
    }
}
