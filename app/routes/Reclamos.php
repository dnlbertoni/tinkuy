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
    $productos = new \Entidad\Producto_model();

    $fechoy=new DateTime();
    $args['fechoy'] = $fechoy->getTimestamp();
    $error=null;
    if(count($provincias->GetAll()->result)>0){
        $args['provincias']=$provincias->GetAll()->result;
        $error .= '0';
    }else{
        $error .= '1';
    };
    if(count($tiposproductos->GetForForm()->result)>0){
        $args['tipoprod'] = $tiposproductos->GetForForm()->result;
        $error .= '0';
    }else{
        $error .= '1';
    };
    if(count($productos->GetAll()->result)>0){
        $error .= '0';
    }else{
        $error .= '1';
    };
    if(count($tiposreclamos->GetAll()->result) > 0){
        $args['tiporecl'] = $tiposreclamos->GetAll()->result;
        $error .= '0';
    }else{
        $error .= '1';
    };
    if(count($lugares->GetAll()->result) > 0){
        $args['lugares'] = $lugares->GetAll()->result;
        $error .= '0';
    }else{
        $error .= '1';
    };
    $args['error']=$error;
    $args['errorcode']=bindec($error);

    if(bindec($error)==0){
        return $this->view->render($response, 'addreclamo.phtml', $args);
    }else{
        return $this->view->render($response, 'error.phtml', $args);
    }
});
$app->post('/reclamo', function (Request $request, Response $response) {
    $proceso = new Entidad\Reclamo_model();
    $datos = $request->getParsedBody();
    $formato = 'd/m/Y';
    $fecha = DateTime::createFromFormat($formato, $datos['fechavto']);
    $datos['fechavto']=$fecha->format('Y-m-d');
    return $response->withJson($proceso->InsertOrUpdate($datos));
});

$app->get('/reclamo/{id}', function (Request $request, Response $response,$args) use($container) {
    $procesos=new \Entidad\Reclamo_model();
    return $response->withJson($procesos->Get($args['id'])->result);
});
