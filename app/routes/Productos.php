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

$app->get('/productos[/{formato}]', function (Request $request, Response $response, $params) use($container) {
    $productos = new \Entidad\Producto_model();
    $formato = (isset($params['formato']))?$params['formato']:null;
    if(isset($params['formato'])){
        switch ($formato){
            case 'html':
                $datos = json_encode($productos->GetAll('"/producto/"')->result);
                $th= (array)$productos->GetAllBootgrid('"/producto/"')->rows[0];
                $th = array_keys($th);
                $args = array(  'datos'=>$datos,
                    'urlData'=>'/productos/bootgrid',
                    'titulo'=>'Productos',
                    'th'=> $th,
                    'linkAdd'=>'/producto');
                return $this->view->render($response, 'grilla.phtml', $args);
                break;
            case 'bootgrid':
                return $response->withJson($productos->GetAllBootgrid('"/producto/"'));
                break;
            default:
                return $response->withJson($productos->GetAll('"/producto/"')->result);
                break;
        }
    }else{
        return $response->withJson($productos->GetAll('"/producto/"')->result);
    }
});
$app->get('/productos/ByTipo/{tipoproducto}', function (Request $request, Response $response, $params) use($container) {
    $productos = new \Entidad\Producto_model();
    return $response->withJson($productos->GetByTipo($params['tipoproducto'])->result);
});

$app->post('/producto', function (Request $request, Response $response) {
    $producto = new Entidad\Producto_model();
    $respuesta['result']=$producto->InsertOrUpdate($request->getParsedBody())->result;
    $base_url = $request->getUri()->getScheme(). '://'.$request->getUri()->getHost().'/';
    $respuesta['urlCallBack']=$base_url.'productos/html';
    return $response->withJson($respuesta);
});

$app->get('/producto', function (Request $request, Response $response) use($container) {
    $args['titulo'] = 'Producto';
    $args['tipoprod'] = array(array('id'=>1, 'nombre'=>'Arroz'),array('id'=>2, 'nombre'=>'Snacks'));
    return $this->view->render($response, 'addproducto.phtml', $args);
});
$app->get('/producto/{id}', function (Request $request, Response $response,$args) use($container) {
    $producto=new \Entidad\Producto_model();
    $base_url = $request->getUri()->getScheme(). '://'.$request->getUri()->getHost().'/';
    return $response->withJson($producto->Get($args['id'],$base_url.'productos/html')->result );
});
