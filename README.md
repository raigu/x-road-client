DRAFT - DRAFT - DRAFT

Useful for communicating over X-road using PSR-7 compatible third-party libraries.


# Usage

```php
<?php


$builder = \Raigu\XRoad\SoapEnvelopeBuilder::create()
    ->withService('EE/GOV/70008440/rr/RR437/v1')
    ->withClient('EE/COM/12213008/gathering')
    ->withBody(<<<EOD
        <prod:RR437 xmlns:prod="http://rr.x-road.eu/producer">
            <request>
                <Isikukood>00000000000</Isikukood>
            </request>
        </prod:RR437>
EOD
    );

$envelope = $builder->build();

$securityServer = \Raigu\XRoad\Psr18XRoadSecurityServer::create(
    'http://test.ee',
    new HttpClientStub
);

$response = $securityServer->process($envelope);

echo $response->asStr();
```