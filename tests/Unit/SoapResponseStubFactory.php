<?php

namespace Raigu\Test\Unit;

use Psr\Http\Message\ResponseInterface;

final class SoapResponseStubFactory
{
    public static function success(): ResponseInterface
    {
        $psr17Factory = new \Nyholm\Psr7\Factory\Psr17Factory();

        return $psr17Factory->createResponse(200)
            ->withBody(
                $psr17Factory->createStream(<<<EOD
<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/">
    <SOAP-ENV:Header/>
    <SOAP-ENV:Body>
        <testServiceResponse/>
    </SOAP-ENV:Body>
</SOAP-ENV:Envelope>
EOD
                )
            );
    }
}
