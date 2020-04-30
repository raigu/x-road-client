<?php

namespace Raigu\Test\Feature;

use PHPUnit\Framework\TestCase;
use Raigu\Test\Unit\HttpClientFake;
use Raigu\Test\Unit\HttpClientStub;
use Raigu\XRoad\Psr18XRoadSecurityServer;
use Raigu\XRoad\SoapEnvelopeBuilder;

class XRoadServiceConsumptionWithPsr18CompatibleLibraryTest extends TestCase
{
    /**
     * @test
     */
    public function end_application_can_consume_X_Road_service()
    {
        $envelope = SoapEnvelopeBuilder::stub()
            ->build();

        $securityServer = Psr18XRoadSecurityServer::create(
            'http://test.ee',
            new HttpClientStub
        );


        $response = $securityServer->process($envelope);

        $this->assertIsString($response->asStr());
    }

    /**
     * @test
     */
    public function throws_exception_if_security_server_returns_none_HTTP_success()
    {
        $securityServer = Psr18XRoadSecurityServer::create(
            'http://test.ee',
            HttpClientFake::rawResponse(
                "HTTP/1.1 532 Stub Error"
            )
        );

        $this->expectExceptionMessage('532');

        $securityServer->process('');
    }
}
