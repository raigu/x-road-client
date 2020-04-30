<?php

namespace Raigu\XRoad;

use Psr\Http\Message\ResponseInterface;

final class SoapEnvelopeAsXRoadServiceResponse implements XRoadServiceResponse
{
    /**
     * @var ResponseInterface
     */
    private $response;

    public function asStr(): string
    {
        $dom = new \DOMDocument;
        $dom->loadXML($this->response->getBody()->getContents());
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

    public static function create(ResponseInterface $response)
    {
        return new self($response);
    }

    /**
     * XRoadServiceResponse constructor.
     */
    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }
}
