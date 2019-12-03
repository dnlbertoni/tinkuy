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

$app->get('/lugarcompras[/{formato}]', function (Request $request, Response $response, $params) use($container) {
    $productos=new \Entidad\Lugarcompra_model();
    $formato = (isset($params['formato']))?$params['formato']:null;
    if(isset($params['formato'])){
        switch ($formato){
            case 'html':
                $datos = json_encode($productos->GetAll('"/lugarcompra/"')->result);
                $th= (array)$productos->GetAllBootgrid('"/lugarcompra/"')->rows[0];
                $th = array_keys($th);
                $args = array(  'datos'=>$datos,
                    'urlData'=>'/lugarcompras/bootgrid',
                    'titulo'=>'Lugares de Compra',
                    'th'=> $th,
                    'linkAdd'=>'/lugarcompra');
                return $this->view->render($response, 'grilla.phtml', $args);
                break;
            case 'bootgrid':
                return $response->withJson($productos->GetAllBootgrid('"/lugarcompra/"'));
                break;
            default:
                return $response->withJson($productos->GetAll('"/lugarcompra/"')->result);
                break;
        }
    }else{
        return $response->withJson($productos->GetAll('"/lugarcompra/"')->result);
    }
});

$app->post('/lugarcompra', function (Request $request, Response $response) {
    $producto=new \Entidad\Lugarcompra_model();
    $estados = new \Entidad\Estados_model();
    $base_url = $request->getUri()->getScheme(). '://'.$request->getUri()->getHost().'/';
    $idestado = $estados->defineEstado('lugarcompra', 'creacion');
    if($idestado>0){
        $creacion = $producto->InsertOrUpdate($request->getParsedBody());
        $respuesta['result']=$creacion->result;
        $base_url = $request->getUri()->getScheme(). '://'.$request->getUri()->getHost().'/';
        $respuesta['urlCallBack']=$base_url.'lugarcompras/html';
    }else{
        $respuesta['result'] = null;
        $respuesta['urlCallBack']=$base_url.'error/6';
    }
    return $response->withJson($respuesta);
});

$app->get('/lugarcompra', function (Request $request, Response $response) use($container) {
    $args['titulo'] = 'Lugar de Compra';
    $args['accion'] = '/lugarcompra';
    return $this->view->render($response, 'add.phtml', $args);
});
$app->get('/lugarcompra/{id}', function (Request $request, Response $response,$args) use($container) {
    $producto=new \Entidad\Lugarcompra_model();
    $base_url = $request->getUri()->getScheme(). '://'.$request->getUri()->getHost().'/';
    return $response->withJson($producto->Get($args['id'],$base_url.'lugarcompras/html')->result );
});
