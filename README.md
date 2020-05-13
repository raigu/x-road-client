[![Latest Stable Version](https://poser.pugx.org/raigu/x-road-client/v/stable)](https://packagist.org/packages/raigu/x-road-client)
[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](LICENSE)
[![build](https://github.com/raigu/x-road-client/workflows/build/badge.svg)](https://github.com/raigu/x-road-client/actions)
[![codecov](https://codecov.io/gh/raigu/x-road-client/branch/master/graph/badge.svg)](https://codecov.io/gh/raigu/x-road-client)


PHP library for consuming X-Road services. It exposes service level logic and hides low level logic (SOAP, HTTP).

The HTTP communication uses [PSR-18](https://www.php-fig.org/psr/psr-18/) compatible client.
Client can be replaced in case full control over HTTP communication is needed.

# Installation

```bash
composer install raigu/x-road-client
```

# Usage in PHP 

You need to know the service name, client's name and URL of client's security.

```php
<?php
$service = \Raigu\XRoad\Service::create(
    $name = 'EE/COM/00000000/SubSys/Service/v0',
    $client = 'EE/COM/00000000/SubSys',
    \Raigu\XRoad\DefaultSecurityServer::create(
        'https://security-server.client.com'
    )
);

$response = $service->request(<<<EOD
    <prod:testService xmlns:prod="http://test.x-road.fi/producer">
        <request>
            <responseBodySize>5</responseBodySize>
         </request>
    </prod:testService>
EOD
);

echo $response; // will output the service provider's response extracted from SOAP envelope 
```

# Command line usage


```bash
# request in file request.xml
$ cat request.xml | php ./bin/request.php service client security_server_url
```
Reads request from STDIN, makes the request and prints out service response.


See script help for more info:

```bash
$ php ./bin/request.php
```

# Developer documentation

## Service request

Service request can be made based on WSDL using tools like [Anayze WSDL](https://www.wsdl-analyzer.com/) or [SoapUI](https://www.soapui.org/).
See [video](https://www.youtube.com/watch?v=ziQIwlTtPLA) how to do it.

WSDL can be downloaded from [X-Road catalog](https://x-tee.ee/catalogue/EE). Use service name to look it up.

## Error handling

The `Service` will throw an exception if:
* actual security server returns HTTP response with status code other than 2xx
* actual security server response contains SOAP Fault.
* other communication problem

## HTTP communication

To create security server instance with your [PSR-18](https://www.php-fig.org/psr/psr-18/) 
compatible client use factory method `\Raigu\XRoad\DefaultSecurityServer::fromPsr18Client`

```php
\Raigu\XRoad\DefaultSecurityServer::create(
    'https://security-server.consumer.com',
    new Client // Any PSR-18 compatible client
);
```

If you have a client that is not PSR-18 compatible but can handle PSR-7 request and response then you 
can write an adapter. For example if you have already installed [Guzzle](https://github.com/guzzle/guzzle/)
package and want to re-use it then you can create an adapter like this:

```php
class GuzzleAdapter implements \Psr\Http\Client\ClientInterface
{
    /**
     * @var \GuzzleHttp\Client
     */
    private $client;

    public function sendRequest(\Psr\Http\Message\RequestInterface $request): \Psr\Http\Message\ResponseInterface
    {
        return $this->client->send($request);
    }

    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client();
    }
}
```

# Demo 

Run local X-Road test server as instructed in this [blog post](https://medium.com/@raigur/how-to-make-x-road-service-requests-manually-for-debugging-purposes-9319b2d5e630).

Then you can consume the test service using this PHP script:

```php

$service = \Raigu\XRoad\Service::create(
    $name = 'NIIS-TEST/GOV/0245437-2/TestService/testService/v1',
    $client = 'NIIS-TEST/GOV/123456-7/testClient',
    \Raigu\XRoad\DefaultSecurityServer::create(
        'http://localhost:8080/test-service-0.0.3/Endpoint'
    )
);

$response = $service->request(<<<EOD
    <prod:testService xmlns:prod="http://test.x-road.fi/producer">
        <request>
            <responseBodySize>5</responseBodySize>
            <responseAttachmentSize>0</responseAttachmentSize>
         </request>
    </prod:testService>
EOD
);

echo $response; // will output the service provider's response extracted from SOAP envelope 
``` 

Executing from command line. First store service request in file `request.xml`:

```xml
<prod:testService xmlns:prod="http://test.x-road.fi/producer">
    <request>
        <responseBodySize>5</responseBodySize>
        <responseAttachmentSize>0</responseAttachmentSize>
    </request>
</prod:testService>
```

Execute the request:

```bash
$ cat request.xml | php ./bin/request.php NIIS-TEST/GOV/0245437-2/TestService/testService/v1 NIIS-TEST/GOV/123456-7/testClient http://localhost:8080/test-service-0.0.3/Endpoint 
```

Script will output the service response:

```xml
<ts1:request xmlns:ts1="http://test.x-road.fi/producer">
    <ts1:responseBodySize>5</ts1:responseBodySize>
    <ts1:responseAttachmentSize>0</ts1:responseAttachmentSize>
</ts1:request>
```

# License

Licensed under [MIT](LICENSE)
