<?php

namespace Raigu\Test;

use PHPUnit\Framework\TestCase;
use Raigu\XRoad\XRoadRequestBuilder;
use Raigu\XRoad\XRoadSecurityServer;

class XRoadServiceConsumptionFeatureTest extends TestCase
{
    /**
     * @test
     */
    public function library_allows_to_consume_X_Road_service()
    {
        $request = XRoadRequestBuilder::create()
            ->withService('')
            ->withClient('')
            ->withBody('')
            ->build();

        $securityServer = XRoadSecurityServer::create('http://test.ee');

        $response = $securityServer->process($request);

        $this->assertIsString($response->asStr());
    }
}
