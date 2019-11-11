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

$app->get('/tiporeclamos[/{formato}]', function (Request $request, Response $response, $params) use($container) {
    $productos=new \Entidad\Tiporeclamo_model();
    $formato = (isset($params['formato']))?$params['formato']:null;
    if(isset($params['formato'])){
        switch ($formato){
            case 'html':
                $datos = json_encode($productos->GetAll('"/tiporeclamo/"')->result);
                $th= (array)$productos->GetAllBootgrid('"/tiporeclamo/"')->rows[0];
                $th = array_keys($th);
                $args = array(  'datos'=>$datos,
                    'urlData'=>'/tiporeclamos/bootgrid',
                    'titulo'=>'Tipos de Reclamos',
                    'th'=> $th,
                    'linkAdd'=>'/tiporeclamo');
                return $this->view->render($response, 'grilla.phtml', $args);
                break;
            case 'bootgrid':
                return $response->withJson($productos->GetAllBootgrid('"/tiporeclamo/"'));
                break;
            default:
                return $response->withJson($productos->GetAll('"/tiporeclamo/"')->result);
                break;
        }
    }else{
        return $response->withJson($productos->GetAll('"/tiporeclamo/"')->result);
    }
});

$app->post('/tiporeclamo', function (Request $request, Response $response) {
    $producto=new \Entidad\Tiporeclamo_model();
    $respuesta['result']=$producto->InsertOrUpdate($request->getParsedBody())->result;
    $base_url = $request->getUri()->getScheme(). '://'.$request->getUri()->getHost().'/';
    $respuesta['urlCallBack']=$base_url.'tiporeclamos/html';
    return $response->withJson($respuesta);
});

$app->get('/tiporeclamo', function (Request $request, Response $response) use($container) {
    $args['titulo'] = 'Tipo de Reclamos';
    return $this->view->render($response, 'addtiporeclamo.phtml', $args);
});
$app->get('/tiporeclamo/{id}', function (Request $request, Response $response,$args) use($container) {
    $producto=new \Entidad\Tiporeclamo_model();
    $base_url = $request->getUri()->getScheme(). '://'.$request->getUri()->getHost().'/';
    return $response->withJson($producto->Get($args['id'],$base_url.'tiporeclamos/html')->result );
});
