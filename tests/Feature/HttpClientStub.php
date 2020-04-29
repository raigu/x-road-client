<?php

namespace Raigu\Test\Feature;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Raigu\Test\Unit\SoapResponseStub;

final class HttpClientStub implements ClientInterface
{
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        return SoapResponseStub::success();
    }
}
