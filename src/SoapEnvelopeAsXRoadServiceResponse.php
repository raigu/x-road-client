<?php

namespace Raigu\XRoad;

use Closure;
use Psr\Http\Message\StreamInterface;

final class SoapEnvelopeAsXRoadServiceResponse implements XRoadServiceResponse
{
    /**
     * @var Closure
     */
    private $envelope;

    public function asStr(): string
    {
        $envelope = call_user_func($this->envelope);

        $dom = new \DOMDocument;
        $dom->loadXML($envelope);
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
        return new self(function () use ($stream): string {
            $stream->rewind();

            return $stream->getContents();
        });
    }

    public static function create(string $envelope)
    {
        return new self(function () use ($envelope): string {
            return $envelope;
        });
    }

    public function __construct(Closure $envelope)
    {
        $this->envelope = $envelope;
    }
}
