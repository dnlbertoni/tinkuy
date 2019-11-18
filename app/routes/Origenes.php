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

$app->get('/origenes[/{formato}]', function (Request $request, Response $response, $params) use($container) {
    $productos=new \Entidad\Origenes_model();
    $formato = (isset($params['formato']))?$params['formato']:null;
    if(isset($params['formato'])){
        switch ($formato){
            case 'html':
                $datos = json_encode($productos->GetAll('"/origen/"')->result);
                $th= (array)$productos->GetAllBootgrid('"/origen/"')->rows[0];
                $th = array_keys($th);
                $args = array(  'datos'=>$datos,
                                'urlData'=>'/origenes/bootgrid',
                                'titulo'=>'Origenes del reclamo',
                                'th'=> $th,
                                'linkAdd'=>'/origen');
                return $this->view->render($response, 'grilla.phtml', $args);
                break;
            case 'bootgrid':
                return $response->withJson($productos->GetAllBootgrid('"/origen/"', '/reclamo/html/'));
                break;
            default:
                return $response->withJson($productos->GetAll('"/origen/"')->result);
                break;
        }
    }else{
        return $response->withJson($productos->GetAll('"/origen/"')->result);
    }
});

$app->post('/origen', function (Request $request, Response $response) {
    $producto=new \Entidad\Origenes_model();
    $creacion = $producto->InsertOrUpdate($request->getParsedBody());
    $respuesta['result']=$creacion->result;
    $base_url = $request->getUri()->getScheme(). '://'.$request->getUri()->getHost().'/';
    $respuesta['urlCallBack']=$base_url.'origenes/html';
    return $response->withJson($respuesta);
});

$app->get('/origen', function (Request $request, Response $response) use($container) {
    $args['titulo'] = 'Origenes del Formulario del Reclamo';
    $args['accion'] = '/origen';
    return $this->view->render($response, 'add.phtml', $args);
});
$app->get('/origen/{id}', function (Request $request, Response $response,$args) use($container) {
    $producto=new \Entidad\Origenes_model();
    $base_url = $request->getUri()->getScheme(). '://'.$request->getUri()->getHost().'/';
    return $response->withJson($producto->Get($args['id'],$base_url.'origenes/html')->result );
});
