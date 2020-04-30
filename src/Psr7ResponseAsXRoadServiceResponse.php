<?php

namespace Raigu\XRoad;

use Psr\Http\Message\ResponseInterface;

final class Psr7ResponseAsXRoadServiceResponse implements XRoadServiceResponse
{
    /**
     * @var XRoadServiceResponse
     */
    private $response;

    public function asStr(): string
    {
        return $this->response->asStr();
    }

    public static function create(ResponseInterface $response): self
    {
        return new self(
            SoapEnvelopeAsXRoadServiceResponse::fromStream(
                $response->getBody()
            )
        );
    }

    private function __construct(XRoadServiceResponse $response)
    {
        $this->response = $response;
    }
}
