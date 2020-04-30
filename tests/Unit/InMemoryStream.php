<?php

namespace Raigu\Test\Unit;

use Psr\Http\Message\StreamInterface;

final class InMemoryStream implements StreamInterface
{
    /**
     * @var string
     */
    private $content;

    public function __toString()
    {
        return $this->content;
    }

    public function close()
    {

    }

    public function detach()
    {

    }

    public function getSize()
    {

    }

    public function tell()
    {

    }

    public function eof()
    {

    }

    public function isSeekable()
    {

    }

    public function seek($offset, $whence = SEEK_SET)
    {

    }

    public function rewind()
    {

    }

    public function isWritable()
    {

    }

    public function write($string)
    {

    }

    public function isReadable()
    {

    }

    public function read($length)
    {

    }

    public function getContents()
    {
        return $this->content;
    }

    public function getMetadata($key = null)
    {

    }

    public static function create(string $content)
    {
        return new self($content);
    }

    public static function empty(): self
    {
        return self::create('');
    }

    private function __construct(string $content)
    {
        $this->content = $content;
    }
}
