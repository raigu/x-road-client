<?php

namespace Raigu\Test\Feature;

use PHPUnit\Framework\TestCase;
use Raigu\XRoad\XRoadService;

final class XRoadServiceRequestTest extends TestCase
{
    /**
     * @test
     */
    public function client_requests_service()
    {
        $service = XRoadService::create(
            $name = 'EE/COM/00000000/SubSys/service/v0',
            $client = 'EE/COM/00000000/SubSys',
            SecurityServerFake::serviceResponse(
                $expected = '<stub_response/>'
            )
        );

        $response = $service->request('<stub_request/>');

        $this->assertXmlStringEqualsXmlString(
            $expected,
            $response
        );
    }
}
