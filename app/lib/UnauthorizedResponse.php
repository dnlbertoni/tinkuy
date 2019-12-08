<?php


namespace App\Lib;

use Crell\ApiProblem\ApiProblem;
use Slim\Http\Headers;
use Slim\Http\Response;
use Slim\Http\Stream;

class UnauthorizedResponse extends Response
{
    public function __construct($message, $status = 401)
    {
        $problem = new ApiProblem($message, "about:blank");
        $problem->setStatus($status);

        $handle = fopen("php://temp", "wb+");
        $body = new Stream($handle);
        $body->write($problem->asJson(true));
        $headers = new Headers;
        $headers->set("Content-type", "application/problem+json");
        parent::__construct($status, $headers, $body);
    }
}
