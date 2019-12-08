<?php
namespace Gofabian\Negotiation;

use RuntimeException;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;

class TestRequest extends TestMessage implements ServerRequestInterface
{

    private $attributes = [];
    private $headers = [];


    public function getAttribute($name, $default = null)
    {
        return $this->attributes[$name];
    }

    public function withAttribute($name, $value)
    {
        $this->attributes[$name] = $value;
        return $this;
    }


    public function hasAttribute($name)
    {
        return array_key_exists($name, $this->attributes);
    }

    public function getHeaderLine($name)
    {
        return isset($this->headers[$name]) ? $this->headers[$name] : '';
    }

    public function withHeader($name, $value)
    {
        $this->headers[$name] = $value;
        return $this;
    }



    // methods not used in tests:

    public function getServerParams()
    {
        $this->unsupported();
    }
    public function getCookieParams()
    {
        $this->unsupported();
    }
    public function withCookieParams(array $cookies)
    {
        $this->unsupported();
    }
    public function getQueryParams()
    {
        $this->unsupported();
    }
    public function withQueryParams(array $query)
    {
        $this->unsupported();
    }
    public function getUploadedFiles()
    {
        $this->unsupported();
    }
    public function withUploadedFiles(array $uploadedFiles)
    {
        $this->unsupported();
    }
    public function getParsedBody()
    {
        $this->unsupported();
    }
    public function withParsedBody($data)
    {
        $this->unsupported();
    }
    public function getAttributes()
    {
        $this->unsupported();
    }
    public function withoutAttribute($name)
    {
        $this->unsupported();
    }

    public function getRequestTarget()
    {
        $this->unsupported();
    }
    public function withRequestTarget($requestTarget)
    {
        $this->unsupported();
    }
    public function getMethod()
    {
        $this->unsupported();
    }
    public function withMethod($method)
    {
        $this->unsupported();
    }
    public function getUri()
    {
        $this->unsupported();
    }
    public function withUri(UriInterface $uri, $preserveHost = false)
    {
        $this->unsupported();
    }


    private function unsupported() {
        throw new RuntimeException("not implemented");
    }

}
