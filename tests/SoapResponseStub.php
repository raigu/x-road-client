<?php

namespace Raigu\Test;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

final class SoapResponseStub implements ResponseInterface
{
    /**
     * @var StreamInterface
     */
    private $body;

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
        return $this->body;
    }

    public function withBody(StreamInterface $body)
    {
        return new self($body);
    }

    public function getStatusCode()
    {

    }

    public function withStatus($code, $reasonPhrase = '')
    {

    }

    public function getReasonPhrase()
    {

    }

    public static function success(): self
    {
        return new self(
            InMemoryStream::create(<<<EOD
<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/">
    <SOAP-ENV:Header/>
    <SOAP-ENV:Body>
        <testServiceResponse/>
    </SOAP-ENV:Body>
</SOAP-ENV:Envelope>
EOD
            )
        );
    }

    private function __construct(StreamInterface $body)
    {
        $this->body = $body;
    }
}