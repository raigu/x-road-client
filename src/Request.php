<?php

namespace Raigu\XRoad;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

/**
 * I am a PSR-7 compatible request
 */
final class Request implements RequestInterface
{
    public function getProtocolVersion()
    {

    }

    public function withProtocolVersion($version)
    {

    }

    public function getHeaders()
    {

    }

    public function hasHeader($name)
    {

    }

    public function getHeader($name)
    {

    }

    public function getHeaderLine($name)
    {

    }

    public function withHeader($name, $value)
    {

    }

    public function withAddedHeader($name, $value)
    {

    }

    public function withoutHeader($name)
    {

    }

    public function getBody()
    {

    }

    public function withBody(StreamInterface $body)
    {

    }

    public function getRequestTarget()
    {

    }

    public function withRequestTarget($requestTarget)
    {

    }

    public function getMethod()
    {

    }

    public function withMethod($method)
    {

    }

    public function getUri()
    {

    }

    public function withUri(UriInterface $uri, $preserveHost = false)
    {

    }
}
