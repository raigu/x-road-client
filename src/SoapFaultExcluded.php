<?php

namespace Raigu\XRoad;

use Psr\Http\Message\ResponseInterface;

final class SoapFaultExcluded
{
    /**
     * @var ResponseInterface
     */
    private $response;

    public function validate(): void
    {
        $body = $this->response->getBody()->getContents();
        if (strpos($body, ":Fault") !== false) {
            $dom = new \DOMDocument;
            $dom->loadXML($body);

            $elements = $dom->getElementsByTagNameNS('http://schemas.xmlsoap.org/soap/envelope/', 'Fault');
            $fault = $elements->item(0);
            $values = [];
            foreach ($fault->childNodes as $element) {
                if ($element->localName === 'faultstring') {
                    $values['faultstring'] = $element->nodeValue;
                } elseif ($element->localName === 'faultcode') {
                    $values['faultcode'] = $element->nodeValue;
                }
            }

            $message = sprintf(
                "%s (faultcode: %s)",
                $values['faultstring'] ?? 'Unknown error. Fault response did not contain faultstring.',
                $values['faultcode'] ?? ''
            );

            throw new \Exception($message);
        }
    }

    public static function create(ResponseInterface $response): self
    {
        return new self($response);
    }

    private function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }
}
