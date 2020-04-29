<?php

namespace Raigu\Test;

use PHPUnit\Framework\TestCase;
use Raigu\XRoad\XRoadSecurityServer;

class XRoadServiceConsumptionFeatureTest extends TestCase
{
    /**
     * @test
     */
    public function end_application_can_consume_X_Road_service()
    {
        $securityServer = XRoadSecurityServer::create('http://test.ee');

        $response = $securityServer->process(
            file_get_contents(
                __DIR__ . '/request-envelope.xml'
            )
        );

        $this->assertIsString($response->asStr());
    }
}
