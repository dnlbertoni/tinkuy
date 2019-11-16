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

$app->get('/estados[/{formato}]', function (Request $request, Response $response, $params) use($container) {
    $productos=new \Entidad\Estados_model();
    $formato = (isset($params['formato']))?$params['formato']:null;
    if(isset($params['formato'])){
        switch ($formato){
            case 'html':
                $datos = json_encode($productos->GetAll('"/estado/"')->result);
                $th= (array)$productos->GetAllBootgrid('"/estado/"')->rows[0];
                $th = array_keys($th);
                $args = array(  'datos'=>$datos,
                    'urlData'=>'/estados/bootgrid',
                    'titulo'=>'Estados',
                    'th'=> $th,
                    'linkAdd'=>'/estado');
                return $this->view->render($response, 'grilla.phtml', $args);
                break;
            case 'bootgrid':
                return $response->withJson($productos->GetAllBootgrid('"/estado/"'));
                break;
            default:
                return $response->withJson($productos->GetAll('"/estado/"')->result);
                break;
        }
    }else{
        return $response->withJson($productos->GetAll('"/estado/"')->result);
    }
});

$app->post('/estado', function (Request $request, Response $response) {
    $producto=new \Entidad\Estados_model();
    $respuesta['result']=$producto->InsertOrUpdate($request->getParsedBody())->result;
    $base_url = $request->getUri()->getScheme(). '://'.$request->getUri()->getHost().'/';
    $respuesta['urlCallBack']=$base_url.'estados/html';
    return $response->withJson($respuesta);
});

$app->get('/estado', function (Request $request, Response $response) use($container) {
    $args['titulo'] = 'Estados';
    return $this->view->render($response, 'addestado.phtml', $args);
});
$app->get('/estado/{id}', function (Request $request, Response $response,$args) use($container) {
    $producto=new \Entidad\Estados_model();
    $base_url = $request->getUri()->getScheme(). '://'.$request->getUri()->getHost().'/';
    return $response->withJson($producto->Get($args['id'],$base_url.'estados/html')->result );
});
