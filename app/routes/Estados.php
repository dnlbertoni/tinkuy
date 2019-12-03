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
                $maquinaestados=new \Entidad\Maquinaestados_model();
                $eventos = new \Entidad\Eventos_model();
                $datos = json_encode($productos->GetAll('"/estado/"')->result);
                $th= (array)$productos->GetAllBootgrid('"/estado/"')->rows[0];
                $th = array_keys($th);
                $th2= (array)$productos->SinRelacion('"/estado/"')->rows[0];
                $th2 = array_keys($th2);
                $args = array(  'datos'=>$datos,
                    'urlData'=>'/estados/bootgrid',
                    'titulo'=>'Estados',
                    'th'=> $th,
                    'linkAdd'=>'/estado',
                    'urlData2'=>'/estados/bootgridSR',
                    'titulo2'=>'Relacion Faltantes',
                    'th2'=> $th2
                );
                return $this->view->render($response, 'grilla.phtml', $args);
                break;
            case 'bootgrid':
                return $response->withJson($productos->GetAllBootgrid('"/estado/"'));
                break;
            case 'bootgridSR':
                return $response->withJson($productos->SinRelacion('"/estado/"'));
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
    $estados=new \Entidad\Estados_model();
    $respuesta['result']=$estados->InsertOrUpdate($request->getParsedBody())->result;
    $base_url = $request->getUri()->getScheme(). '://'.$request->getUri()->getHost().'/';
    $respuesta['urlCallBack']=$base_url.'estados/html';
    return $response->withJson($respuesta);
});

$app->get('/estado/{idmaquinaestado}/{idevento}', function (Request $request, Response $response, $param) use($container) {
    $args['titulo'] = 'Estados';
    $maquinaestados=new \Entidad\Maquinaestados_model();
    $me=$maquinaestados->Get($param['idmaquinaestado'])->result;
    $error=($me === null)?'1': '0';
    $eventos = new \Entidad\Eventos_model();
    $e=$eventos->Get($param['idevento'])->result;
    $error .=($e === null)?'1': '0';
    $args['error']=$error;
    $args['errorcode']=bindec($error);
    $args['maquinaestados']=$me;
    $args['eventos']=$e;
    if(bindec($error)==0){
        return $this->view->render($response, 'addestado.phtml', $args);
    }else{
        return $this->view->render($response, 'error.phtml', $args);
    }
});
$app->get('/estado/{id}', function (Request $request, Response $response,$args) use($container) {
    $producto=new \Entidad\Estados_model();
    $base_url = $request->getUri()->getScheme(). '://'.$request->getUri()->getHost().'/';
    return $response->withJson($producto->Get($args['id'],$base_url.'estados/html')->result );
});
