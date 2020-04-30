<?php

namespace Raigu\Test\Feature;

use PHPUnit\Framework\TestCase;
use Raigu\XRoad\SoapEnvelopeBuilder;
use Raigu\XRoad\XRoadSecurityServer;

class XRoadServiceConsumptionTest extends TestCase
{
    /**
     * @test
     */
    public function end_application_can_consume_X_Road_service()
    {
        $envelope = SoapEnvelopeBuilder::stub()
            ->build();

        $securityServer = XRoadSecurityServer::create(
            'http://test.ee',
            new HttpClientStub
        );


        $response = $securityServer->process($envelope);

        $this->assertIsString($response->asStr());
    }
}
