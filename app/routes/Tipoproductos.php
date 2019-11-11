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

$app->get('/tipoproductos[/{formato}]', function (Request $request, Response $response, $params) use($container) {
    $productos=new \Entidad\Tipoproducto_model();
    $formato = (isset($params['formato']))?$params['formato']:null;
    if(isset($params['formato'])){
        switch ($formato){
            case 'html':
                $datos = json_encode($productos->GetAll('"/tipoproducto/"')->result);
                $th= (array)$productos->GetAll('"/tipoproducto/"')->result[0];
                $th = array_keys($th);
                $args = array(  'datos'=>$datos,
                    'urlData'=>'/tipoproductos/bootgrid',
                    'titulo'=>'Tipos de Productos',
                    'th'=> $th,
                    'linkAdd'=>'/tipoproducto');
                return $this->view->render($response, 'grilla.phtml', $args);
                break;
            case 'bootgrid':
                return $response->withJson($productos->GetAllBootgrid('"/tipoproducto/"'));
                break;
            default:
                return $response->withJson($productos->GetAll('"/tipoproducto/"')->result);
                break;
        }
    }else{
        return $response->withJson($productos->GetAll('"/tipoproducto/"')->result);
    }
});

$app->post('/tipoproducto', function (Request $request, Response $response) {
    $producto=new \Entidad\Tipoproducto_model();
    $respuesta['result']=$producto->InsertOrUpdate($request->getParsedBody())->result;
    $base_url = $request->getUri()->getScheme(). '://'.$request->getUri()->getHost().'/';
    $respuesta['urlCallBack']=$base_url.'tipoproductos/html';
    return $response->withJson($respuesta);
});

$app->get('/tipoproducto', function (Request $request, Response $response) use($container) {
    $args['titulo'] = 'Tipo de Producto';
    return $this->view->render($response, 'addtipoproducto.phtml', $args);
});
$app->get('/tipoproducto/{id}', function (Request $request, Response $response,$args) use($container) {
    $producto=new \Entidad\Tipoproducto_model();
    $base_url = $request->getUri()->getScheme(). '://'.$request->getUri()->getHost().'/';
    return $response->withJson($producto->Get($args['id'],$base_url.'tipoproductos/html')->result );
});
