<?php

use Slim\Http\Request;
use Slim\Http\Response;


$app->group('/api', function() use($app){
    $app->group('/v1', function ($app){
        $app->group('/productos', function($app){
            $app->get('/bootgrid', function (Request $request, Response $response, $params){
                $productos = new \Entidad\Producto_model();
                return $response->withJson($productos->GetAllBootgrid('"/producto/"'));
            });
            $app->get('/ByTipo/{tipoproducto}', function (Request $request, Response $response, $params) {
                $productos = new \Entidad\Producto_model();
                return $response->withJson($productos->GetByTipo($params['tipoproducto'])->result);
            });
            $app->get('[/{id}]', function (Request $request, Response $response,$args) {
                $productos=new \Entidad\Producto_model();
                if(isset($args['id'])){
                    $base_url = $request->getUri()->getScheme(). '://'.$request->getUri()->getHost().'/';
                    return $response->withJson($productos->Get($args['id'],$base_url.'productos/html')->result );
                }else{
                    return $response->withJson($productos->GetAll('"/producto/"')->result);
                }
            });
            $app->post('/', function (Request $request, Response $response) {
                $producto = new Entidad\Producto_model();
                $respuesta['result']=$producto->InsertOrUpdate($request->getParsedBody())->result;
                $base_url = $request->getUri()->getScheme(). '://'.$request->getUri()->getHost().'/';
                $respuesta['urlCallBack']=$base_url.'productos/html';
                return $response->withJson($respuesta);
            });
        });
        $app->group('/lugarescompra', function ($app){
            $app->get('/bootgrid', function (Request $request, Response $response, $params) {
                $productos=new \Entidad\Lugarcompra_model();
                return $response->withJson($productos->GetAllBootgrid('"/lugarcompra/"'));
            });
            $app->get('/{id}', function (Request $request, Response $response,$args) {
                $lugares=new \Entidad\Lugarcompra_model();
                $base_url = $request->getUri()->getScheme(). '://'.$request->getUri()->getHost().'/';
                if(isset($args['id'])){
                    return $response->withJson($lugares->Get($args['id'],$base_url.'lugarcompras/html')->result );
                }else{
                    return $response->withJson($lugares->GetAll('"/lugarcompra/"'));
                }
            });
            $app->post('/', function (Request $request, Response $response) {
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
        });
        $app->group('/tiposproductos', function ($app){
            $app->get('/bootgrid', function (Request $request, Response $response, $params) {
                $tipoproductos=new \Entidad\Tipoproducto_model();
                return $response->withJson($tipoproductos->GetAllBootgrid('"/tipoproducto/"'));
            });
            $app->post('/', function (Request $request, Response $response) {
                $producto=new \Entidad\Tipoproducto_model();
                $estados = new \Entidad\Estados_model();
                $base_url = $request->getUri()->getScheme(). '://'.$request->getUri()->getHost().'/';
                $idestado = $estados->defineEstado('tipoproductos', 'creacion');
                if($idestado>0){
                    $respuesta['result']=$producto->InsertOrUpdate($request->getParsedBody())->result;
                    $respuesta['urlCallBack']=$base_url.'tipoproductos/html';
                }else{
                    $respuesta['result'] = null;
                    $respuesta['urlCallBack']=$base_url.'error/6';
                }
                return $response->withJson($respuesta);
            });
            $app->get('/tipoproducto/{id}', function (Request $request, Response $response,$args){
                $producto=new \Entidad\Tipoproducto_model();
                $base_url = $request->getUri()->getScheme(). '://'.$request->getUri()->getHost().'/';
                if(isset($args['id'])){
                    return $response->withJson($producto->Get($args['id'],$base_url.'tipoproductos/html')->result );
                }else{
                    return $response->withJson($producto->GetAll($base_url.'tipoproductos/html') );
                }
            });
        });
        $app->group('/tiposreclamos',function($app){
            $app->get('/bootgrid', function (Request $request, Response $response, $params){
                $tiposreclamos=new \Entidad\Tiporeclamo_model();
                return $response->withJson($tiposreclamos->GetAllBootgrid('"/tiporeclamo/"'));
            });
            $app->post('/', function (Request $request, Response $response) {
                $producto=new \Entidad\Tiporeclamo_model();
                $estados = new \Entidad\Estados_model();
                $base_url = $request->getUri()->getScheme(). '://'.$request->getUri()->getHost().'/';
                $idestado = $estados->defineEstado('tiporeclamos', 'creacion');
                if($idestado>0){
                    $respuesta['result']=$producto->InsertOrUpdate($request->getParsedBody())->result;
                    $base_url = $request->getUri()->getScheme(). '://'.$request->getUri()->getHost().'/';
                    $respuesta['urlCallBack']=$base_url.'tiporeclamos/html';
                }else{
                    $respuesta['result'] = null;
                    $respuesta['urlCallBack']=$base_url.'error/6';
                }
                return $response->withJson($respuesta);
            });
            $app->get('/{id}', function (Request $request, Response $response,$args) {
                $tiposreclamos=new \Entidad\Tiporeclamo_model();
                if(isset($args['id'])){
                    $base_url = $request->getUri()->getScheme(). '://'.$request->getUri()->getHost().'/';
                    return $response->withJson($tiposreclamos->Get($args['id'],$base_url.'tiporeclamos/html')->result );
                }else{
                    return $response->withJson($tiposreclamos->GetAll('"/tiporeclamo/"'));
                };
            });
        });
        $app->group('/reclamos', function ($app){
            $app->get('/bootgrid', function (Request $request, Response $response, $params) {
                $reclamos = new \Entidad\Reclamo_model();
                return $response->withJson($reclamos->GetAllBootgrid('/reclamo/'));
            });
            $container = $app->getContainer();
            $container['view'] = function ($container) {
                $view = new \Slim\Views\Twig(__DIR__ . '/../../templates/');

                // Instantiate and add Slim specific extension
                $router = $container->get('router');
                $uri = \Slim\Http\Uri::createFromEnvironment(new \Slim\Http\Environment($_SERVER));
                $view->addExtension(new \Slim\Views\TwigExtension($router, $uri));

                return $view;
            };
            $app->get('/html/{origen}', function (Request $request, Response $response, $param) use($container) {
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
                $args['accion']='/reclamos';
                if($error==0){
                    return $this->view->render($response, 'reclamo/add.phtml', $args);
                }else{
                    return $response->withStatus(302)->withHeader('Location', '/error/'.$error);
                }
            });
            $app->post('/', function (Request $request, Response $response) {
                $proceso = new Entidad\Reclamo_model();
                $datos = $request->getParsedBody();
                $formato = 'd/m/Y';
                $fecha = DateTime::createFromFormat($formato, $datos['fechavto']);
                $datos['fechavto']=$fecha->format('Y-m-d');
                return $response->withJson($proceso->CrearReclamo($datos));
            });
            $app->get('/{id}[/{accion}]', function (Request $request, Response $response,$args)  {
                $reclamos=new \Entidad\Reclamo_model();
                $tiposproductos = new \Entidad\Tipoproducto_model();
                $productos = new \Entidad\Producto_model();
                $tiposreclamos = new \Entidad\Tiporeclamo_model();
                $provincias = new \Entidad\Provincias_model();
                $lugares = new \Entidad\Lugarcompra_model();
                $origen = new \Entidad\Origenes_model();
                if(!isset($args['id'])){
                    return $response->withJson($reclamos->GetAll());
                }else{
                    $reclamo = $reclamos->Get($args['id'])->result;
                    if(isset($args['accion'])){
                        if(isset($args['accion'])){
                            switch ($args['accion']){
                                case 'ver':
                                    return $response->withJson($reclamos->Get($args['id'])->result);
                                    break;
                                default:
                                    $error = 11;
                                    return $response->withStatus(302)->withHeader('Location', '/error/'.$error);
                                    break;
                            }
                        }else{
                            return $response->withJson($reclamos->Get($args['id'])->result);
                        }
                    }else{
                        return $response->withJson($reclamo);
                    }
                }
            });
        });
    });
});

