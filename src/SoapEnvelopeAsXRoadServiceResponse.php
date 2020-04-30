<?php

namespace Raigu\XRoad;

use Psr\Http\Message\StreamInterface;
use Raigu\Test\Unit\InMemoryStream;

final class SoapEnvelopeAsXRoadServiceResponse implements XRoadServiceResponse
{
    /**
     * @var StreamInterface
     */
    private $envelope;

    public function asStr(): string
    {
        $dom = new \DOMDocument;
        $dom->loadXML($this->envelope->getContents());
        $elements = $dom->getElementsByTagNameNS(
            'http://schemas.xmlsoap.org/soap/envelope/',
            'Body'
        );

        //@see https://stackoverflow.com/a/42994559/1412737
        $xpath = new \DOMXpath($dom);
        $firstElementChild = $xpath->evaluate('./*[1]', $elements->item(0))[0];

        if ($firstElementChild->namespaceURI) {
            $firstElementChild->setAttributeNS(
                'http://www.w3.org/2000/xmlns/',
                'xmlns:' . $firstElementChild->prefix,
                $firstElementChild->namespaceURI
            );
        }

        return $dom->saveXml($firstElementChild);
    }

    public static function fromStream(StreamInterface $stream): self
    {
        return new self($stream);
    }

    public static function create(string $envelope)
    {
        return new self(InMemoryStream::create($envelope));
    }

    public function __construct(StreamInterface $envelope)
    {
        $this->envelope = $envelope;
    }
}
