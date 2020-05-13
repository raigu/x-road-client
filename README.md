[![Latest Stable Version](https://poser.pugx.org/raigu/x-road-client/v/stable)](https://packagist.org/packages/raigu/x-road-client)
[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](LICENSE)
[![build](https://github.com/raigu/x-road-client/workflows/build/badge.svg)](https://github.com/raigu/x-road-client/actions)
[![codecov](https://codecov.io/gh/raigu/x-road-client/branch/master/graph/badge.svg)](https://codecov.io/gh/raigu/x-road-client)


PHP library for consuming X-Road services. Gives high-level interface for end-application yet allows to control it in low level using
[PSR-7](https://www.php-fig.org/psr/psr-7/) and [PSR-18](https://www.php-fig.org/psr/psr-18/) compatible interfaces.

# Installation

```bash
composer install raigu/x-road-client
```

# Usage 

You need to know the service name, client's name and client's security server.

```php
<?php
$service = \Raigu\XRoad\Service::create(
    $name = '/EE/COM/00000000/SubSys/service/v0',
    $client = '/EE/COM/00000000/SubSys',
    \Raigu\XRoad\Psr18SecurityServer::create(
        'https://security-server.consumer.com',
        new Client
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

If you want to play with this library before using it then there is a [demo application](https://github.com/raigu/x-road-client-demo) using local X-Road test server in docker container.

# Developer documentation

## X-Road request construction

The input of security server instance is plain SOAP envelope meeting the [X-Road Message requirements](https://www.x-tee.ee/docs/live/xroad/pr-mess_x-road_message_protocol.html#e1-request). 
You can create it by yourself or use a builder which hides the SOAP logic and asks only X-road service related information.
The builder `\Raigu\XRoad\SoapEnvelopeBuilder` is taken from package [raigu/x-road-soap-envelope-builder](https://github.com/raigu/x-road-soap-envelope-builder).
Please see the package documentation for more information about how to use this builder.

## Error handling

Proper response means that service provider received request, processed and returned the response backed into
X-Road (SOAP) message. 

The security server will throw an exception if:
* actual security server returns HTTP response with status code other than 2xx
* actual security server response contains SOAP Fault.

## HTTP communication

The HTTP communication is handed over to [PSR-18](https://www.php-fig.org/psr/psr-18/) compatible HTTP client which 
must be provided explicitly.
 
This gives more control over library. It is possible to debug, log or alter the behaviour. For example, it
 is possible to implement specific error handling, provide API key to security server in HTTP header, 
 inject test double responses for tests etc. 

You can use any PSR-18 compatible client (for example [php-http/curl-client](https://github.com/php-http/curl-client)) 
or create an adapter for [PSR-7](https://www.php-fig.org/psr/psr-7/) compatible library by yourself.  
For example adapter for [Guzzle](https://github.com/guzzle/guzzle/):

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

# License

Licensed under [MIT](LICENSE)
