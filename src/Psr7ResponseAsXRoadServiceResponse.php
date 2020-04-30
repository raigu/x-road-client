<?php

namespace Raigu\XRoad;

use Psr\Http\Message\ResponseInterface;

final class Psr7ResponseAsXRoadServiceResponse implements XRoadServiceResponse
{
    /**
     * @var ResponseInterface
     */
    private $response;

    public function asStr(): string
    {
        return SoapEnvelopeAsXRoadServiceResponse::create(
            $this->response
        )->asStr();
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
