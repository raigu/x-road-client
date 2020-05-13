<?php

namespace Raigu\Test\Feature;

use Raigu\XRoad\Requestable;

final class SecurityServerFake implements Requestable
{
    /**
     * @var string
     */
    private $responseEnvelope;

    public function request(string $request): string
    {
        return $this->responseEnvelope;
    }

    public static function serviceResponse(string $serviceResponse): self
    {
        return new self(<<<EOD
<env:Envelope xmlns:env="http://schemas.xmlsoap.org/soap/envelope/"
              xmlns:id="http://x-road.eu/xsd/identifiers"
              xmlns:xrd="http://x-road.eu/xsd/xroad.xsd">
   <env:Header>
      <xrd:client id:objectType="SUBSYSTEM">
         <id:xRoadInstance>NIIS-TEST</id:xRoadInstance>
         <id:memberClass>GOV</id:memberClass>
         <id:memberCode>123456-7</id:memberCode>
         <id:subsystemCode>TestClient</id:subsystemCode>
      </xrd:client>
      <xrd:service id:objectType="SERVICE">
         <id:xRoadInstance>NIIS-TEST</id:xRoadInstance>
         <id:memberClass>GOV</id:memberClass>
         <id:memberCode>0245437-2</id:memberCode>
         <id:subsystemCode>TestService</id:subsystemCode>
         <id:serviceCode>testService</id:serviceCode>
         <id:serviceVersion>v1</id:serviceVersion>
      </xrd:service>
      <xrd:id>67322f5c-8c74-4b12-bbcb-073618f30b1b</xrd:id>
      <xrd:protocolVersion>?</xrd:protocolVersion>
   </env:Header>
   <env:Body>{$serviceResponse}</env:Body>
</env:Envelope>
EOD
);
    }

    private function __construct(string $responseEnvelope)
    {
        $this->responseEnvelope = $responseEnvelope;
    }
}
