<?php

use Slim\App;

use App\lib\Token;
use Gofabian\Negotiation\NegotiationMiddleware;
use Micheh\Cache\CacheUtil;
use Tuupola\Middleware\JwtAuthentication;
use Tuupola\Middleware\HttpBasicAuthentication;
use Tuupola\Middleware\CorsMiddleware;
use App\lib\UnauthorizedResponse;

return function (App $app) {
    // e.g: $app->add(new \Slim\Csrf\Guard);

    $container = $app->getContainer();

    $container["HttpBasicAuthentication"] = function ($container) {
        return new HttpBasicAuthentication([
            "path" => "/",
            "ignore"=>"/",
            "relaxed" => ["127.0.0.1", "localhost"],
            "error" => function ($response, $arguments) {
                return new UnauthorizedResponse($arguments["message"], 401);
            },
            "users" => [
                "test" => "test"
            ]
        ]);
    };

    $container["Token"] = function ($container) {
        return new Token([]);
    };

    $container["JwtAuthentication"] = function ($container) {
        return new JwtAuthentication([
            "path" => "/api",
            "ignore" => ["/api/v1/user/login", "/api/v1/user/registrar"],
            "secret" => getenv("JWT_SECRET"),
            "logger" => $container["logger"],
            "attribute" => false,
            "relaxed" => [ "127.0.0.1", "localhost"],
            "error" => function ($response, $arguments) {
                return new UnauthorizedResponse($arguments["message"], 401);
            },
            "before" => function ($request, $arguments) use ($container) {
                $container["Token"]->populate($arguments["decoded"]);
            }
        ]);
    };
    $container["CorsMiddleware"] = function ($container) {
        return new CorsMiddleware([
//            "logger" => $container["logger"],
            "origin" => ["*"],
            "methods" => ["GET", "POST", "PUT", "PATCH", "DELETE"],
            "headers.allow" => ["Authorization", "If-Match", "If-Unmodified-Since"],
            "headers.expose" => ["Authorization", "Etag"],
            "credentials" => true,
            "cache" => 60,
            "error" => function ($request, $response, $arguments) {
                return new UnauthorizedResponse($arguments["message"], 401);
            }
        ]);
    };

    $container["NegotiationMiddleware"] = function ($container) {
        return new NegotiationMiddleware([
            "accept" => ["application/json"]
        ]);
    };

    $app->add("HttpBasicAuthentication");
    $app->add("JwtAuthentication");
    $app->add("CorsMiddleware");
    $app->add("NegotiationMiddleware");

    $container["cache"] = function ($container) {
        return new CacheUtil;
    };
/*
    $app->add(new \Slim\Middleware\Session([
        'name' => 'tinkuy',
        'autorefresh' => true,
        'lifetime' => '1 hour'
    ]));
*/
};


