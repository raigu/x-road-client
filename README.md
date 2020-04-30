PHP library for consuming X-Road services using third-party HTTP [PSR-18](https://www.php-fig.org/psr/psr-18/) 
or [PSR-7](https://www.php-fig.org/psr/psr-7/) compatible libraries.

The purpose of this library is to help client application in X-Road request construction, 
communicating with security server, extracting actual service provider response from SOAP message 
and error detection. 

# Installation

```bash
composer install raigu/x-road-client
```

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
    'http://x-road.security.server.company.com',
    new Client // Any PSR-8 compatible HTTP client. Must be installed or implemented separately.
);

$response = $securityServer->process($envelope);

echo $response->asStr();
```

# HTTP communication

The HTTP communication is handed over to [PSR-18](https://www.php-fig.org/psr/psr-18/) compatible HTTP client which 
must be provided explicitly.
 
This gives more control over library. It is possible to debug, log or alter the behaviour. For example, it
 is possible to implement specific error handling, provide API key to security server in HTTP header, 
 inject test double responses for tests etc. 

You can use any PSR-18 compatible client (for example [php-http/curl-client](https://github.com/php-http/curl-client)) 
or create an adapter for [PSR-7](https://www.php-fig.org/psr/psr-7/) compatible library by yourself.  For example adapter for [Guzzle](https://github.com/guzzle/guzzle/):

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
