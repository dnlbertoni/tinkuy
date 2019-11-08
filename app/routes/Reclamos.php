<?php

use Slim\Http\Request;
use Slim\Http\Response;

$container = $app->getContainer();

$app->get('/procesos', function (Request $request, Response $response, array $args)   {
    $procesos=new \Entidad\Reclamo_model();
    return $response->withJson($procesos->GetAll('"/proceso/"')->result);
});
$app->get('/procesos/html', function (Request $request, Response $response) use($container) {
    $dat = new \Entidad\Reclamo_model();
    $datos = json_encode($dat->GetAll('"/proceso/"')->result);
    $th= (array)$dat->GetAll('"/proceso/"')->result[0];
    $th = array_keys($th);
    $args = array(  'datos'=>$datos,
                    'urlData'=>'/procesos/bootgrid',
                    'titulo'=>'Procesos',
                    'th'=> $th,
                    'linkAdd'=>'/proceso');
    return $container->get('renderer')->render($response, 'grilla.phtml', $args);
});

$app->get('/procesos/bootgrid', function (Request $request, Response $response) use($container) {
    $procesos = new \Entidad\Reclamo_model();
    return $response->withJson($procesos->GetAllBootgrid('"/proceso/"'));
});

$app->post('/proceso', function (Request $request, Response $response) {
    $proceso = new Entidad\Reclamo_model();
    return $response->withJson($proceso->InsertOrUpdate($request->getParsedBody()));
});

$app->get('/proceso', function (Request $request, Response $response) use($container) {
    $macroprocesos= new \Entidad\Macroproceso_model();
    $selMacro= $macroprocesos->GetAll();
    $responsables= new \Entidad\Responsable_model();
    $selResp= $responsables->GetAll();
    $args = array('macroprocesos'=>$selMacro->result, 'responsables'=>$selResp->result);
    return $container->get('renderer')->render($response, 'addreclamo.phtml', $args);
});
$app->get('/proceso/{id}', function (Request $request, Response $response,$args) use($container) {
    $procesos=new \Entidad\Reclamo_model();
    return $response->withJson($procesos->Get($args['id'])->result);
});
