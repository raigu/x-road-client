<?php

namespace Raigu\Test\Unit;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

/**
 * I am a raw HTTP Response which acts as PSR-7 compatible response
 *
 * I will implement only the part needed in tests.
 */
final class RawHttpResponseAsPsr7Response implements ResponseInterface
{
    /**
     * @var string
     */
    private $response;

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
        // @source https://www.w3.org/Protocols/rfc2616/rfc2616-sec6.html
        $p = strpos($this->response,"\r\n\r\n");

        return InMemoryStream::create(
            substr($this->response, $p+4)
        );
    }

    public function withBody(StreamInterface $body)
    {

    }

    public function getStatusCode()
    {
        // @source https://www.w3.org/Protocols/rfc2616/rfc2616-sec6.html
        preg_match('/^HTTP\/\d.\d\s+(\d+)\s.*/', $this->response, $matches);

        return intval($matches[1]);
    }

    public function withStatus($code, $reasonPhrase = '')
    {

    }

    public function getReasonPhrase()
    {

    }

    public static function fromFile(string $fileName): self
    {
        return self::create(
            file_get_contents(
                $fileName
            )
        );
    }

    public static function create(string $response)
    {
        return new self($response);
    }

    private function __construct(string $response)
    {
        $this->response = $response;
    }
}
