<?php

namespace Raigu\XRoad;

use Nyholm\Psr7\Response;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * I am PSR-18 adapter for curl
 */
final class CurlPsr18Adapter implements ClientInterface
{
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $url = $request->getUri();
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);

        $body = $request->getBody();
        $body->rewind();
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body->getContents());

        $headers = [];
        foreach ($request->getHeaders() as $name => $values) {
            foreach ($values as $value) {
                $headers[] = sprintf('%s: %s', $name, $value);
            }
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $body = curl_exec($ch);

        $errno = curl_errno($ch);
        if ($errno) {
            throw new \Exception(
                sprintf(
                    'Could not make request to %s. (%s) %s.',
                    $url,
                    $errno,
                    curl_error($ch)
                )
            );
        }

        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return new Response(
            $code,
            [],
            $body
        );
    }
}
