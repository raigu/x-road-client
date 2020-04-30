<?php

namespace Raigu\Test\Unit;

use PHPUnit\Framework\TestCase;

class HttpResponseAsPsr7ResponseTest extends TestCase
{
    /**
     * @test
     */
    public function extracts_status_code()
    {
        $sut = RawHttpResponseAsPsr7Response::create(<<<EOD
HTTP/1.1 301 Moved Permanently
Content-Length: 0
EOD
        );

        $this->assertSame(
            301,
            $sut->getStatusCode()
        );
    }

    /**
     * @test
     */
    public function extracts_body()
    {
        $sut = RawHttpResponseAsPsr7Response::create(
            "HTTP/1.1 201 OK\r\n" .
            "Content-Length: 3\r\n" .
            "Content-Type: text/plain\r\n" .
            "\r\n" .
            "ABC"
        );

        $this->assertEquals(
            'ABC',
            $sut->getBody()
        );
    }
}
