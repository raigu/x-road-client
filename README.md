DRAFT - DRAFT - DRAFT

Useful for communicating over X-road using PSR-7 compatible third-party libraries.


# Usage

```php
<?php

$request = XRoadRequestBuilder::create()
    ->withService('')
    ->withBody()
    ->withClient('')
    ->build();

$securityServer = XRoadSecurityServer::create($url);

$response = $securityServer->process($request);

$response->body();
```