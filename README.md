[![Latest Stable Version](https://poser.pugx.org/raigu/x-road-client/v/stable)](https://packagist.org/packages/raigu/x-road-client)
[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](LICENSE)
[![build](https://github.com/raigu/x-road-client/workflows/build/badge.svg)](https://github.com/raigu/x-road-client/actions)
[![codecov](https://codecov.io/gh/raigu/x-road-client/branch/master/graph/badge.svg)](https://codecov.io/gh/raigu/x-road-client)


PHP library for consuming X-Road services. 

It exposes service level request and response to the end application and hides low level logic (SOAP, HTTP).

The HTTP communication uses [PSR-18](https://www.php-fig.org/psr/psr-18/) compatible client which must be installed separately.
This approach gives full control over HTTP layer if needed.


# Installation

```bash
composer install raigu/x-road-client
```

# Usage 

```php
<?php
$service = \Raigu\XRoad\Service::create(
    'EE/COM/00000000/SubSys/Service/v0', // service name
    'EE/COM/00000000/SubSys', // client name
    \Raigu\XRoad\SecurityServer::create(
        'https://security-server.client.com', // client's security server
        new Client // Any PSR-18 compatible client.    
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


# Documentation

## Service Request

Service request can be made based on WSDL using tools like [Anayze WSDL](https://www.wsdl-analyzer.com/) or [SoapUI](https://www.soapui.org/).
See [video](https://www.youtube.com/watch?v=ziQIwlTtPLA) how to create one.

WSDL can be downloaded from [X-Road catalog](https://x-tee.ee/catalogue/EE). Use service name to look it up.

## Error Handling

The `Service` will throw an exception if:
* received response with status code other than 2xx
* received SOAP Fault.
* other communication problem

## HTTP Communication

If you have a client which is not [PSR-18](https://www.php-fig.org/psr/psr-18/) compatible but can handle PSR-7 request and response then you 
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

One way to check this library out is to set up local X-Road test server in docker and make a test request.

Install library locally.

```bash
$ git clone git@github.com:raigu/x-road-client.git
$ cd x-road-client
$ composer install
```

Execute X-Road test server in docker container
```bash
$ docker run --rm -p 8080:8080 petkivim/x-road-test-service:v0.0.3
```

Install PSR-18 client. In current sample we will be using 
```bash
$ composer require php-http/curl-client
```

Create a file `request.php` in project's root directory:

```php
<?php
require_once 'vendor/autoload.php';

$service = \Raigu\XRoad\Service::create(
    $name = 'NIIS-TEST/GOV/0245437-2/TestService/testService/v1',
    $client = 'NIIS-TEST/GOV/123456-7/testClient',
    \Raigu\XRoad\SecurityServer::create(
        'http://localhost:8080/test-service-0.0.3/Endpoint', // Win users: use your ip. Execute "docker-machine ip"
        new Http\Client\Curl\Client
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

Execute the script:

```bash
$ php request.php
```

You should see output:

```xml
<ts1:request xmlns:ts1="http://test.x-road.fi/producer">
    <ts1:responseBodySize>5</ts1:responseBodySize>
    <ts1:responseAttachmentSize>0</ts1:responseAttachmentSize>
</ts1:request>
```

See my [blog post](https://medium.com/@raigur/how-to-make-x-road-service-requests-manually-for-debugging-purposes-9319b2d5e630) for more info about testing X-Road services manually.

# License

Licensed under [MIT](LICENSE)
