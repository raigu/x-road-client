<?php

namespace Raigu\XRoad;

use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamFactoryInterface;

/**
 * I am a factory. I create PSR-7 RequestInterface from SOAP envelope
 * @see https://www.php-fig.org/psr/psr-7/
 */
final class Psr7RequestFactory
{

    /**
     * @var RequestFactoryInterface
     */
    private $requestFactory;
    /**
     * @var StreamFactoryInterface
     */
    private $streamFactory;

    public function fromSoapEnvelope(string $uri, string $envelope): RequestInterface
    {
        return $this->requestFactory->createRequest('POST', $uri)
            ->withHeader('Content-Type', 'text/xml; charset="utf-8"')
            ->withHeader('SOAPAction', '')
            ->withBody(
                $this->streamFactory->createStream($envelope)
            );
    }

    public static function default()
    {
        $factory = new \Nyholm\Psr7\Factory\Psr17Factory();

        return new self($factory, $factory);
    }

    private function __construct(RequestFactoryInterface $requestFactory, StreamFactoryInterface $streamFactory)
    {
        $this->requestFactory = $requestFactory;
        $this->streamFactory = $streamFactory;
    }
}
