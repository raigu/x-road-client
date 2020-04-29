<?php

use PHPUnit\Framework\TestCase;
use Raigu\XRoad\XRoadSecurityServer;
use Raigu\XRoad\XRoadSoapRequestBuilder;

class XRoadServiceConsumptionFeatureTest extends TestCase
{
    /**
     * @test
     */
    public function library_allows_to_consume_X_Road_service()
    {
        $request = XRoadSoapRequestBuilder::create()
            ->withService('')
            ->withClient('')
            ->withBody('')
            ->build();

        $securityServer = XRoadSecurityServer::create('http://www.neti.ee');

        $response = $securityServer->process($request);

        $this->assertIsString($response->asStr());
    }
}
