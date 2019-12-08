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
    $session = new \SlimSession\Helper;
    if(!$session->exists('token')){
        return $response->withStatus(200)->withHeader('Location', '/');
    };
    $reclamos = new \Entidad\Reclamo_model();
    $formato = (isset($params['formato']))?$params['formato']:null;
    switch ($formato){
        case 'html':
            $datos = json_encode($reclamos->GetAll('"/reclamo/"')->result);
            $th= (array)$reclamos->GetAllBootgrid('"/reclamo/"')->rows[0];
            $th = array_keys($th);
            $args = array(  'datos'=>$datos,
                'urlData'=>'/reclamos/bootgrid',
                'titulo'=>'Reclamos',
                'th'=> $th,
                'linkAdd'=>'/reclamo/html/aWQ6MQ==');
            return $this->view->render($response, 'grilla.phtml', $args);
            break;
        case 'tablero':
            $datos = $reclamos->GetAllBootgrid('"/reclamo/"');
            $flujoEstados = new \Entidad\Estados_model();
            $args = array(  'datos'   => $datos,
                            'flujo'   => $flujoEstados->flujoEstados(),
                            'titulo'  => 'Reclamos en Gestion',
                            'usuario' => $session->get('usuario'),
                            'token'   => $session->get('token'),
                            'linkAdd' => '/reclamo/html/aWQ6MQ==');
            return $this->view->render($response, 'reclamo/tablero.phtml', $args);
            break;
        case 'bootgrid':
            return $response->withJson($reclamos->GetAllBootgrid('/reclamo/'));
            break;
        default:
            return $response->withJson($reclamos->GetAll('"/reclamo/"')->result);
            break;
    }
});

$app->get('/reclamo/html/{origen}', function (Request $request, Response $response, $param) use($container) {
    $tiposproductos = new \Entidad\Tipoproducto_model();
    $tiposreclamos = new \Entidad\Tiporeclamo_model();
    $provincias = new \Entidad\Provincias_model();
    $lugares = new \Entidad\Lugarcompra_model();
    $productos = new \Entidad\Producto_model();
    $origen = new \Entidad\Origenes_model();
    $estado = new \Entidad\Estados_model();

    $fechoy=new DateTime();
    $args['fechoy'] = $fechoy->getTimestamp();
    /**** validaciones ***/
    $error=null;
    if(count($provincias->GetAll()->result)>0){ //error 1
        $args['provincias']=$provincias->GetAll()->result;
        if(count($tiposproductos->GetForForm()->result)>0){ //error 2
            $args['tipoprod'] = $tiposproductos->GetForForm()->result;
            if(count($productos->GetAll()->result)>0){ //error 3
                if(count($tiposreclamos->GetAll()->result) > 0){ //error 4
                    $args['tiporecl'] = $tiposreclamos->GetAll()->result;
                    if(count($lugares->GetAll()->result) > 0){ //error 5
                        $args['lugares'] = $lugares->GetAll()->result;
                        if(count($origen->GetByHash($param['origen']))>0){ //error 7
                            $args['origen']=$param['origen'];
                            if($estado->defineEstado('reclamos','creacion') > 0){ //error 6
                                $error = 0;
                            }else{
                                $error = 6;
                            };
                        }else{
                            $error =  7;
                        };
                    }else{
                        $error = 5;
                    };
                }else{
                    $error = 4;
                };
            }else{
                $error = 3;
            };
        }else{
            $error = 2;
        };
    }else{
        $error = 1;
    };
    if(($origen->GetByHash($param['origen']) != 1)){ // 1 es API
        $args['embebed'] = true;
    }
    $args['accion']='/reclamo';
    if($error==0){
        return $this->view->render($response, 'reclamo/add.phtml', $args);
    }else{
        return $response->withStatus(302)->withHeader('Location', '/error/'.$error);
    }
});
$app->post('/reclamo', function (Request $request, Response $response) {
    $proceso = new Entidad\Reclamo_model();
    $datos = $request->getParsedBody();
    $formato = 'd/m/Y';
    $fecha = DateTime::createFromFormat($formato, $datos['fechavto']);
    $datos['fechavto']=$fecha->format('Y-m-d');
    return $response->withJson($proceso->CrearReclamo($datos));
});

$app->get('/reclamo/{id}[/{accion}]', function (Request $request, Response $response,$args) use($container) {
    $reclamos=new \Entidad\Reclamo_model();
    $tiposproductos = new \Entidad\Tipoproducto_model();
    $productos = new \Entidad\Producto_model();
    $tiposreclamos = new \Entidad\Tiporeclamo_model();
    $provincias = new \Entidad\Provincias_model();
    $lugares = new \Entidad\Lugarcompra_model();
    $origen = new \Entidad\Origenes_model();
    $id=$args['id'];
    if(isset($args['accion'])){
        switch ($args['accion']){
            case 'ver':
                $reclamo = $reclamos->Get($id)->result;
                $args['provincias']=$provincias->GetAll()->result;
                $args['lugares'] = $lugares->GetAll()->result;
                $args['productos'] = $productos->GetByTipo($reclamo->tipoprod)->result;
                //var_dump($productos->GetByTipo($reclamo->tipoprod)->result);die();
                $args['tipoprod'] = $tiposproductos->GetAll()->result;
                $args['tiporecl'] = $tiposreclamos->GetAll()->result;
                $args['origenes'] = $origen->GetAll()->result;
                $args['dato']=$reclamo;
                $args['back']='/reclamos/tablero';
                return $this->view->render($response, 'reclamo/add.phtml', $args);
                break;
            case 'diag':
                $args['titulo']='Analisis del Reclmao';
                $args['back']='/reclamos/tablero';
                $textos[] = array('id'=>'dictamen', 'label'=>'Dictamen', 'icono'=>'fa-note');
                $args['accion']='/reclamo/'.$id.'/diag';
                $args['textos']=$textos;
                return $this->view->render($response, 'reclamo/modal.phtml', $args);
                break;
            case 'notif':
                $args['titulo']='Notificacion al Cliente';
                $args['back']='/reclamos/tablero';
                $combos[] = array(  'id'=>'contacto_cliente',
                                    'label'=>'Medio de Contacto',
                                    'combo'=>array( array('id'=>'Email', 'name'=>'Email'),
                                                    array('id'=>'Telefono', 'name'=>'Telefono'),
                                                    array('id'=>'Email y Telefono', 'name'=>'Email y Telefono')));
                $args['combos']=$combos;
                $args['accion']='/reclamo/'.$id.'/notif';
                return $this->view->render($response, 'reclamo/modal.phtml', $args);
                break;
            case 'envio':
                $args['titulo']='Envio Caja al Cliente';
                $args['back']='/reclamos/tablero';
                $textos[] = array('id'=>'nrotack', 'label'=>'Numero Envio OCA', 'icono'=>'fa-barcode');
                $args['accion']='/reclamo/'.$id.'/envio';
                $args['textos']=$textos;
                return $this->view->render($response, 'reclamo/modal.phtml', $args);
                break;
            case 'recepcion':
                $args['titulo']='Recepcion de  Caja por el Cliente';
                $args['back']='/reclamos/tablero';
                //$textos[] = array('id'=>'nrotack', 'label'=>'Numero Envio OCA', 'icono'=>'fa-barcode');
                $args['accion']='/reclamo/'.$id.'/recepcion';
                //$args['textos']=$textos;
                return $this->view->render($response, 'reclamo/modal.phtml', $args);
                break;
            case 'resolucion':
                $args['titulo']='Resolusion del Reclamo';
                $args['back']='/reclamos/tablero';
                $combos[] = array(  'id'=>'contacto_cliente',
                    'label'=>'Medio de Contacto',
                    'combo'=>array( array('id'=>'Email', 'name'=>'Email'),
                        array('id'=>'Telefono', 'name'=>'Telefono'),
                        array('id'=>'Email y Telefono', 'name'=>'Email y Telefono')));
                $args['combos']=$combos;
                $args['accion']='/reclamo/'.$id.'/resolucion';
                return $this->view->render($response, 'reclamo/modal.phtml', $args);
                break;
            default:
                $error = 11;
                return $response->withStatus(302)->withHeader('Location', '/error/'.$error);
                break;
        }
    }else{
        $reclamo = $reclamos->Get($id)->result;
        return $response->withJson($reclamo);
    }
});

$app->post('/reclamo/{id}[/{accion}]', function (Request $request, Response $response,$args) use($container) {
    $reclamos=new \Entidad\Reclamo_model();
    $tiposproductos = new \Entidad\Tipoproducto_model();
    $productos = new \Entidad\Producto_model();
    $tiposreclamos = new \Entidad\Tiporeclamo_model();
    $provincias = new \Entidad\Provincias_model();
    $lugares = new \Entidad\Lugarcompra_model();
    $origen = new \Entidad\Origenes_model();
    $id=$args['id'];
    if(isset($args['accion'])){
        switch ($args['accion']){
            case 'ver':
                $reclamo = $reclamos->Get($id)->result;
                $args['provincias']=$provincias->GetAll()->result;
                $args['lugares'] = $lugares->GetAll()->result;
                $args['productos'] = $productos->GetByTipo($reclamo->tipoprod)->result;
                //var_dump($productos->GetByTipo($reclamo->tipoprod)->result);die();
                $args['tipoprod'] = $tiposproductos->GetAll()->result;
                $args['tiporecl'] = $tiposreclamos->GetAll()->result;
                $args['origenes'] = $origen->GetAll()->result;
                $args['dato']=$reclamo;
                $args['back']='/reclamos/tablero';
                return $this->view->render($response, 'reclamo/add.phtml', $args);
                break;
            case 'diag':
                $error= $reclamos->analizar($id,1);
                if($error->response != 0){
                    return $response->withStatus(200)->withHeader('Location', '/error/'.$error->response);
                }else{
                    return $response->withStatus(200)->withHeader('Location', '/reclamos/tablero');
                }
                break;
            case 'notif':
                $datos=$request->getParsedBody();
                $error= $reclamos->notificar($id,1);
                if($error->response != 0){
                    return $response->withStatus(200)->withHeader('Location', '/error/'.$error->response);
                }else{
                    return $response->withStatus(200)->withHeader('Location', '/reclamos/tablero');
                }
                break;
            case 'envio':
                $datos=$request->getParsedBody();
                $error= $reclamos->enviar($id,$datos['nrotack']);
                if($error->response != 0){
                    return $response->withStatus(200)->withHeader('Location', '/error/'.$error->response);
                }else{
                    return $response->withStatus(200)->withHeader('Location', '/reclamos/tablero');
                }
                break;
            case 'recepcion':
                $datos=$request->getParsedBody();
                $error= $reclamos->recibir($id);
                if($error->response != 0){
                    return $response->withStatus(200)->withHeader('Location', '/error/'.$error->response);
                }else{
                    return $response->withStatus(200)->withHeader('Location', '/reclamos/tablero');
                }
                break;
            case 'resolucion':
                $datos=$request->getParsedBody();
                $error= $reclamos->resolver($id, $datos['dictamen']);
                if($error->response != 0){
                    return $response->withStatus(200)->withHeader('Location', '/error/'.$error->response);
                }else{
                    return $response->withStatus(200)->withHeader('Location', '/reclamos/tablero');
                }
                break;
            default:
                $error = 11;
                return $response->withStatus(302)->withHeader('Location', '/error/'.$error);
                break;
        }
    }else{
        $reclamo = $reclamos->Get($id)->result;
        return $response->withJson($reclamo);
    }
});
