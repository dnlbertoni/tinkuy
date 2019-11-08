<?php

use Slim\Http\Request;
use Slim\Http\Response;

$container = $app->getContainer();

$app->get('/reclamos', function (Request $request, Response $response, array $args)   {
    $procesos=new \Entidad\Reclamo_model();
    return $response->withJson($procesos->GetAll('"/reclamos/"')->result);
});
$app->get('/reclamos/html', function (Request $request, Response $response) use($container) {
    $dat = new \Entidad\Reclamo_model();
    $datos = json_encode($dat->GetAll('"/proceso/"')->result);
    $th= (array)$dat->GetAll('"/reclamos/"')->result[0];
    $th = array_keys($th);
    $args = array(  'datos'=>$datos,
                    'urlData'=>'/reclamos/bootgrid',
                    'titulo'=>'Reclamos',
                    'th'=> $th,
                    'linkAdd'=>'/proceso');
    return $container->get('renderer')->render($response, 'grilla.phtml', $args);
});

$app->get('/reclamos/bootgrid', function (Request $request, Response $response) use($container) {
    $procesos = new \Entidad\Reclamo_model();
    return $response->withJson($procesos->GetAllBootgrid('"/reclamos/"'));
});

$app->post('/reclamos', function (Request $request, Response $response) {
    $proceso = new Entidad\Reclamo_model();
    return $response->withJson($proceso->InsertOrUpdate($request->getParsedBody()));
});

$app->get('/reclamos', function (Request $request, Response $response) use($container) {
    $macroprocesos= new \Entidad\Macroproceso_model();
    $selMacro= $macroprocesos->GetAll();
    $responsables= new \Entidad\Responsable_model();
    $selResp= $responsables->GetAll();
    $args = array('macroprocesos'=>$selMacro->result, 'responsables'=>$selResp->result);
    return $container->get('renderer')->render($response, 'addreclamo.phtml', $args);
});
$app->get('/reclamos/{id}', function (Request $request, Response $response,$args) use($container) {
    $procesos=new \Entidad\Reclamo_model();
    return $response->withJson($procesos->Get($args['id'])->result);
});
