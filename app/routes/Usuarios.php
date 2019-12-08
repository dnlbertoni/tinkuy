<?php

use Slim\Http\Request;
use Slim\Http\Response;

$container = $app->getContainer();

$app->get('/', function (Request $request, Response $response, array $args) use($container)   {
    $session = new \SlimSession\Helper;
    if($session->exists('token')){
        return $response->withStatus(200)->withHeader('Location', '/reclamos/tablero');
    }else{
        return $this->view->render($response, 'user/login.phtml', $args);
    }
    //return $this->view->render($response, 'index.phtml', $args);
});

$app->get('/login', function (Request $request, Response $response, array $args) use($container)   {
    return $this->view->render($response, 'user/login.phtml', $args);
});
$app->get('/login/ok/{param}', function (Request $request, Response $response, array $args) use($container)   {
    $datos=json_decode(base64_decode($args['param']));
    $session = new \SlimSession\Helper;
    $session->set('token', $datos->token);
    $session->set('usuario', $datos->usuario);
    return $response->withStatus(200)->withHeader('Location', '/');
});
$app->post('/logout', function (Request $request, Response $response) {
    $session = new \SlimSession\Helper;
    $session->destroy();
    $respuesta = array('codigo'=>0, "mensaje"=>'No ocurrio ningun error');
    return $response->withJson($respuesta);
});

$app->get('/registrar', function (Request $request, Response $response, array $args) use($container)   {
    return $this->view->render($response, 'user/registrar.phtml', $args);
});
