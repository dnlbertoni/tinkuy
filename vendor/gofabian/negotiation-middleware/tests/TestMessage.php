<?php
namespace Gofabian\Negotiation;

use RuntimeException;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;

class TestMessage implements MessageInterface
{

    public function getProtocolVersion()
    {
        $this->unsupported();
    }
    public function withProtocolVersion($version)
    {
        $this->unsupported();
    }
    public function getHeaders()
    {
        $this->unsupported();
    }
    public function hasHeader($name)
    {
        $this->unsupported();
    }
    public function getHeader($name)
    {
        $this->unsupported();
    }
    public function getHeaderLine($name)
    {
        $this->unsupported();
    }
    public function withHeader($name, $value)
    {
        $this->unsupported();
    }
    public function withAddedHeader($name, $value)
    {
        $this->unsupported();
    }
    public function withoutHeader($name)
    {
        $this->unsupported();
    }
    public function getBody()
    {
        $this->unsupported();
    }
    public function withBody(StreamInterface $body)
    {
        $this->unsupported();
    }

    private function unsupported() {
        throw new RuntimeException("not implemented");
    }

}
