<?php

namespace Raigu\Test\Unit;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class HttpClientFake implements ClientInterface
{
    /**
     * @var ResponseInterface
     */
    private $response;

    public static function responseFromFile(string $fileName): self
    {
        return new self(
            RawHttpResponseAsPsr7Response::fromFile(
                $fileName
            )
        );
    }

    public static function rawResponse(string $content): self
    {
        return new self(
            RawHttpResponseAsPsr7Response::create(
                $content
            )
        );
    }

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        return $this->response;
    }

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }
}
