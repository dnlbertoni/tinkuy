<?php

use Slim\Http\Request;
use Slim\Http\Response;

$container = $app->getContainer();


$app->get('/', function (Request $request, Response $response, array $args) use($container)   {
    $args['pru']=getenv(DB_NAME);
    return $this->view->render($response, 'index.phtml', $args);
});

$app->get('/login', function (Request $request, Response $response, array $args) use($container)   {
    return $this->view->render($response, 'user/login.phtml', $args);
});

$app->get('/registrar', function (Request $request, Response $response, array $args) use($container)   {
    return $this->view->render($response, 'user/registrar.phtml', $args);
});
$app->post('/registrar', function (Request $request, Response $response)   {
    $usuario=new \Entidad\Usuario_model();
    $datos = $request->getParsedBody();
    if($datos['password']===$datos['pass1']){
        http_response_code(200);
        $respuesta = $usuario->InsertOrUpdate($datos);
    }else{
        http_response_code(401);
        $respuesta = 'Las contraseñas no coinciden';
    }
    return $response->withJson($respuesta);
});