<?php
namespace Gofabian\Negotiation;

use Psr\Http\Message\ResponseInterface;

class TestResponse extends TestMessage implements ResponseInterface
{

    private $statusCode = 200;
    private $reasonPhrase = '';

    public function withStatus($code, $reasonPhrase = '')
    {
        $this->statusCode = $code;
        return $this;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function getReasonPhrase()
    {
        return $this->reasonPhrase;
    }

}
