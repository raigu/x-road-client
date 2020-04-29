DRAFT - DRAFT - DRAFT

Useful for communicating over X-road using PSR-7 compatible third-party libraries.


# Usage

```php
<?php
$request = XRoadSoapRequestBuilder::create()
    ->withSecurityServer('')
    ->withService('')
    ->withClient('')
    ->withBody()
    ->build();

$securityServer = XRoadSecurityServer::create($url);

$response = $securityServer->ask($request);

$response = XRoadSoapResponse::create($response);

$response->serviceResponse(); 
```