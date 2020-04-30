<?php


namespace Raigu\XRoad;

/**
 * I am a response of X-Road service provider.
 *
 * I contain only the raw information service provider sent.
 * I do not contain X-Road infrastructure specific information.
 *
 * I am useful for end applications who are interested only
 * in service provider response and do not want to be
 * distracted by other X-Road meta data information.
 *
 * For example if service provider sends response using SOAP
 * then I will take the Body content. I will discard all
 * Header information.
 */
interface XRoadServiceResponse
{
    /**
     * @return string the raw response sent by service provider over X-road
     */
    public function asStr(): string;
}