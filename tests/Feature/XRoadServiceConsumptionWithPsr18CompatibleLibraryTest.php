<?php

namespace Raigu\Test\Feature;

use PHPUnit\Framework\TestCase;
use Raigu\Test\Unit\HttpClientFake;
use Raigu\Test\Unit\HttpClientStub;
use Raigu\XRoad\DefaultSecurityServer;
use Raigu\XRoad\SoapEnvelope\SoapEnvelopeBuilder;

class XRoadServiceConsumptionWithPsr18CompatibleLibraryTest extends TestCase
{
    /**
     * @test
     */
    public function end_application_can_consume_X_Road_service()
    {
        $envelope = SoapEnvelopeBuilder::stub()
            ->build();

        $securityServer = DefaultSecurityServer::create(
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
        $securityServer = DefaultSecurityServer::create(
            'http://test.ee',
            HttpClientFake::rawResponse(
                "HTTP/1.1 532 Stub Error"
            )
        );

        $this->expectExceptionMessage('532');

        $securityServer->process('');
    }

    /**
     * @test
     */
    public function throws_exception_if_SOAP_fault_received()
    {
        //@source https://www.w3.org/TR/2000/NOTE-SOAP-20000508/#_Ref477795995
        $response = "HTTP/1.1 500 Internal Server Error\r\n" .
            "Content-Type: text/xml; charset=\"utf-8\"\r\n" .
            "Content-Length: nnnn\r\n" .
            "\r\n" .
            "<SOAP-ENV:Envelope\r\n" .
            "  xmlns:SOAP-ENV=\"http://schemas.xmlsoap.org/soap/envelope/\">" .
            "   <SOAP-ENV:Body>" .
            "       <SOAP-ENV:Fault>" .
            "           <faultcode>SOAP-ENV:MustUnderstand</faultcode>" .
            "           <faultstring>SOAP Must Understand Error</faultstring>" .
            "       </SOAP-ENV:Fault>\r\n" .
            "   </SOAP-ENV:Body>" .
            "</SOAP-ENV:Envelope>";

        $securityServer = DefaultSecurityServer::create(
            'http://test.ee',
            HttpClientFake::rawResponse($response)
        );

        $this->expectExceptionMessage('MustUnderstand');
        $this->expectExceptionMessage('SOAP Must Understand Error');

        $securityServer->process('');
    }
}
