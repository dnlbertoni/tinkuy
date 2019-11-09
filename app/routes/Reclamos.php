<?php

use Slim\Http\Request;
use Slim\Http\Response;

$container = $app->getContainer();
// Register component on container
$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig(__DIR__ . '/../../templates/');

    // Instantiate and add Slim specific extension
    $router = $container->get('router');
    $uri = \Slim\Http\Uri::createFromEnvironment(new \Slim\Http\Environment($_SERVER));
    $view->addExtension(new \Slim\Views\TwigExtension($router, $uri));

    return $view;
};

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

$app->get('/reclamo', function (Request $request, Response $response) use($container) {
    $fechoy=new DateTime();
    $args['fechoy'] = $fechoy->getTimestamp();
    $args['tipoprod'] = array(array('id'=>1, 'nombre'=>'Arroz'),array('id'=>2, 'nombre'=>'Snacks'));
    $args['productos'] = array(array('id'=>1, 'nombre'=>'largo fino'), array('id'=>2, 'nombre'=>'Doble Carolina'));
    return $this->view->render($response, 'addreclamo.phtml', $args);
});
$app->get('/reclamo/{id}', function (Request $request, Response $response,$args) use($container) {
    $procesos=new \Entidad\Reclamo_model();
    return $response->withJson($procesos->Get($args['id'])->result);
});
