<?php

use Slim\Http\Request;
use Slim\Http\Response;

$container = $app->getContainer();

$app->get('/responsables', function (Request $request, Response $response, array $args)   {
    $procesos=new \Entidad\Responsable_model();
    return $response->withJson($procesos->GetAll('"/responsable/"')->result);
});
$app->get('/responsables/html', function (Request $request, Response $response) use($container) {
    $dat = new \Entidad\Responsable_model();
    $datos = json_encode($dat->GetAll('"/responsable/"')->result);
    $th= (array)$dat->GetAll('"/responsable/"')->result[0];
    $th = array_keys($th);
    $args = array(  'datos'=>$datos,
                    'urlData'=>'/responsables/bootgrid',
                    'titulo'=>'Responsables',
                    'th'=> $th,
                    'linkAdd'=>'/responsable');
    return $container->get('renderer')->render($response, 'grilla.phtml', $args);
});

$app->get('/responsables/bootgrid', function (Request $request, Response $response) use($container) {
    $procesos = new \Entidad\Responsable_model();
    return $response->withJson($procesos->GetAllBootgrid('"/responsable/"'));
});

$app->post('/responsable', function (Request $request, Response $response) {
    $proceso = new Entidad\Responsable_model();
    return $response->withJson($proceso->InsertOrUpdate($request->getParsedBody()));
});

$app->get('/responsable', function (Request $request, Response $response) use($container) {
    $args = array();
    return $container->get('renderer')->render($response, 'addusuario.phtml', $args);
});
$app->get('/responsable/{id}', function (Request $request, Response $response,$args) use($container) {
    $procesos=new \Entidad\Responsable_model();
    return $response->withJson($procesos->Get($args['id'])->result);
});
