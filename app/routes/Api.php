<?php

$app->group('/api', function() use($app){
    $app->group('/v1', function ($app){
        $app->get('/productos', function (Request $request, Response $response, $params) {
            $productos = new \Entidad\Producto_model();
            return $response->withJson($productos->GetAll()->result);
        });
        $app->get('/productos/ByTipo/{tipoproducto}', function (Request $request, Response $response, $params) {
            $productos = new \Entidad\Producto_model();
            return $response->withJson($productos->GetByTipo($params['tipoproducto'])->result);
        });
    });
});

