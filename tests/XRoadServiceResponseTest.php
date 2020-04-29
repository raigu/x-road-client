<?php

namespace Raigu\Test;

use DOMDocument;
use PHPUnit\Framework\TestCase;
use Raigu\XRoad\XRoadServiceResponse;

final class XRoadServiceResponseTest extends TestCase
{
    /**
     * @test
     */
    public function extracts_soap_body()
    {
        $response = SoapResponseStub::success()
            ->withBody(
                InMemoryStream::create(<<<EOD
<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/">
    <SOAP-ENV:Header/>
    <SOAP-ENV:Body>
        <ts1:testServiceResponse xmlns:ts1="http://test.x-road.fi/producer"/>
    </SOAP-ENV:Body>
</SOAP-ENV:Envelope>
EOD
                )
            );

        $sut = XRoadServiceResponse::create($response);

        $dom = new DOMDocument;
        $dom->loadXML($sut->asStr());
        $this->assertEquals(
            'testServiceResponse',
            $dom->documentElement->localName
        );
    }
}
