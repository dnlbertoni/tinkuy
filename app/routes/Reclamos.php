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

$app->get('/reclamos[/{formato}]', function (Request $request, Response $response, $params) use($container) {
    $reclamos = new \Entidad\Reclamo_model();
    $formato = (isset($params['formato']))?$params['formato']:null;
    switch ($formato){
        case 'html':
            $datos = json_encode($reclamos->GetAll('"/reclamo/"')->result);
            $th= (array)$reclamos->GetAll('"/reclamo/"')->result[0];
            $th = array_keys($th);
            $args = array(  'datos'=>$datos,
                'urlData'=>'/reclamos/bootgrid',
                'titulo'=>'Reclamos',
                'th'=> $th,
                'linkAdd'=>'/reclamo');
            return $this->view->render($response, 'grilla.phtml', $args);
            break;
        case 'bootgrid':
            return $response->withJson($reclamos->GetAllBootgrid('"/reclamos/"'));
            break;
        default:
            return $response->withJson($reclamos->GetAll('"/reclamos/"')->result);
            break;
    }
});

$app->get('/reclamo', function (Request $request, Response $response) use($container) {
    $tiposproductos = new \Entidad\Tipoproducto_model();
    $tiposreclamos = new \Entidad\Tiporeclamo_model();
    $provincias = new \Entidad\Provincias_model();
    $lugares = new \Entidad\Lugarcompra_model();

    $fechoy=new DateTime();
    $args['fechoy'] = $fechoy->getTimestamp();
    $args['provincias']=$provincias->GetAll()->result;
    $args['tipoprod'] = $tiposproductos->GetAll()->result;
    $args['tiporecl'] = $tiposreclamos->GetAll()->result;
    $args['lugares'] = $lugares->GetAll()->result;
    return $this->view->render($response, 'addreclamo.phtml', $args);
});
$app->post('/reclamo', function (Request $request, Response $response) {
    $proceso = new Entidad\Reclamo_model();
    return $response->withJson($proceso->InsertOrUpdate($request->getParsedBody()));
});

$app->get('/reclamo/{id}', function (Request $request, Response $response,$args) use($container) {
    $procesos=new \Entidad\Reclamo_model();
    return $response->withJson($procesos->Get($args['id'])->result);
});
