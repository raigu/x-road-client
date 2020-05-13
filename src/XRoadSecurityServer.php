<?php


namespace Raigu\XRoad;


/**
 * I am a X-Road security server which can take
 * X-Road SOAP requests, route it to service provider
 * and return the service provider's response.
 */
interface XRoadSecurityServer
{
    public function soapRequest(string $envelope): string;

    public function process(string $soapEnvelope): XRoadServiceResponse;
}