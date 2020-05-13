[![Latest Stable Version](https://poser.pugx.org/raigu/x-road-client/v/stable)](https://packagist.org/packages/raigu/x-road-client)
[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](LICENSE)
[![build](https://github.com/raigu/x-road-client/workflows/build/badge.svg)](https://github.com/raigu/x-road-client/actions)
[![codecov](https://codecov.io/gh/raigu/x-road-client/branch/master/graph/badge.svg)](https://codecov.io/gh/raigu/x-road-client)


PHP library for consuming X-Road services. Allows end-application to operate only with service level request and 
response and hides lower level logic (SOAP, HTTP). 

The HTTP communication is done using  [PSR-18](https://www.php-fig.org/psr/psr-18/) compatible client and end-application
can replace the client in order to listen or manipulate [PSR-7](https://www.php-fig.org/psr/psr-7/) compatible request and response.

# Installation

```bash
composer install raigu/x-road-client
```

# Usage in PHP 

You need to know the service name, client's name and client's security server.

```php
<?php
$service = \Raigu\XRoad\Service::create(
    $name = '/EE/COM/00000000/SubSys/service/v0',
    $client = '/EE/COM/00000000/SubSys',
    \Raigu\XRoad\DefaultSecurityServer::create(
        'https://security-server.consumer.com'
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

# Usage from command line

There is a script for reading X-road request from STDIN, making request and printing out service response.

Usage: php ./bin/request.php service client security_server_url

See command help for more info:

```bash
$ php ./bin/request.php
```

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

The HTTP communication is handed over to [PSR-18](https://www.php-fig.org/psr/psr-18/) compatible HTTP client.
To create security server instance with your PSR-18 client use factory method _\Raigu\XRoad\DefaultSecurityServer::fromPsr18Client_.

```php
\Raigu\XRoad\DefaultSecurityServer::create(
    'https://security-server.consumer.com',
    new Client // Any PSR-18 compatible client
);
```

You can use any PSR-18 compatible client (for example [php-http/curl-client](https://github.com/php-http/curl-client)) 
or create an adapter by yourself. For example if you have already installed [Guzzle](https://github.com/guzzle/guzzle/)
package and want to use it then you can create an adapter for it:

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
