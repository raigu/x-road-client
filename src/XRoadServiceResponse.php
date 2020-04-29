<?php

namespace Raigu\XRoad;

use Psr\Http\Message\ResponseInterface;

final class XRoadServiceResponse
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

        foreach ($elements->item(0)->childNodes as $node) {
            if ($node->nodeType === XML_ELEMENT_NODE) {
                return $dom->saveXML($node);
            }
        }
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
