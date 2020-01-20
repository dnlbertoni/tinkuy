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

$app->group('/productos', function ($app) use ($container){
    $app->get('[/]', function (Request $request, Response $response, $params) use($container) {
        $session = new \SlimSession\Helper;
        $productos = new \Entidad\Producto_model();
        $datos = json_encode($productos->GetAll('"/producto/"')->result);
        $th= (array)$productos->GetAllBootgrid('"/producto/"')->rows[0];
        $th = array_keys($th);
        $args = array(  'datos'=>$datos,
            'urlData'=>'/api/v1/productos/bootgrid',
            'titulo'=>'Productos',
            'usuario' => $session->get('usuario'),
            'token'   => $session->get('token'),
            'th'=> $th,
            'linkAdd'=>'/producto');
        return $this->view->render($response, 'grilla.phtml', $args);
    });
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
    $tipoproductos = new \Entidad\Tipoproducto_model();
    $estados = new \Entidad\Estados_model();
    $base_url = $request->getUri()->getScheme(). '://'.$request->getUri()->getHost().'/';
    $idestado = $estados->defineEstado('productos', 'creacion');
    if($idestado>0){
        if(count($tipoproductos->GetAll()->result)>0){
            $error=0;
        }else{
            $error = 2;
        }
    }else{
        $error = 6;
    }
    if($error ==0){
        $args['titulo'] = 'Producto';
        $args['tipoprod'] = $tipoproductos->GetAll()->result;
        return $this->view->render($response, 'producto/add.phtml', $args);
    }else{
        return $response->withStatus(302)->withHeader('Location', '/error/'.$error);
    }
});
$app->get('/producto/{id}', function (Request $request, Response $response,$args) use($container) {
    $producto=new \Entidad\Producto_model();
    $base_url = $request->getUri()->getScheme(). '://'.$request->getUri()->getHost().'/';
    return $response->withJson($producto->Get($args['id'],$base_url.'productos/html')->result );
});
