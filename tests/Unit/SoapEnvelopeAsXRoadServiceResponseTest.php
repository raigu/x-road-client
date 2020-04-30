<?php

namespace Raigu\Test\Unit;

use DOMDocument;
use PHPUnit\Framework\TestCase;
use Raigu\XRoad\SoapEnvelopeAsXRoadServiceResponse;

final class SoapEnvelopeAsXRoadServiceResponseTest extends TestCase
{
    /**
     * @test
     */
    public function extracts_soap_body()
    {
        $sut = SoapEnvelopeAsXRoadServiceResponse::create(<<<EOD
<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/">
                        <SOAP-ENV:Header/>
                        <SOAP-ENV:Body>
                            <ts1:testServiceResponse xmlns:ts1="http://test.x-road.fi/producer"/>
                        </SOAP-ENV:Body>
                    </SOAP-ENV:Envelope>
EOD
        );

        $dom = new DOMDocument;
        $dom->loadXML($sut->asStr());
        $this->assertEquals(
            'testServiceResponse',
            $dom->documentElement->localName
        );
    }

    /**
     * @test
     */
    public function add_namespace_definition_if_defined_outside_SOAP_Body_content()
    {
        $sut = SoapEnvelopeAsXRoadServiceResponse::create(<<<EOD
<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/"
                            xmlns:ts1="http://test.x-road.fi/producer">
                        <SOAP-ENV:Header/>
                        <SOAP-ENV:Body>
                            <ts1:testServiceResponse/>
                        </SOAP-ENV:Body>
                    </SOAP-ENV:Envelope>
EOD
        );

        $this->assertStringStartsWith(
            '<ts1:testServiceResponse xmlns:ts1="http://test.x-road.fi/producer"',
            $sut->asStr()
        );
    }
}
