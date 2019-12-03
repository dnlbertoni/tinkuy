<?php
use Slim\Http\Request;
use Slim\Http\Response;

$app->group('/api', function() use($app){
    $app->group('/v1', function ($app){
        $app->get('/productos[/{formato}]', function (Request $request, Response $response, $params){
            $productos = new \Entidad\Producto_model();
            $formato = (isset($params['formato']))?$params['formato']:null;
            if(isset($params['formato'])){
                switch ($formato){
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
        $app->get('/productos/ByTipo/{tipoproducto}', function (Request $request, Response $response, $params) {
            $productos = new \Entidad\Producto_model();
            return $response->withJson($productos->GetByTipo($params['tipoproducto'])->result);
        });
        $app->get('/producto/{id}', function (Request $request, Response $response,$args) {
            $producto=new \Entidad\Producto_model();
            if(isset($args['id'])){
                $base_url = $request->getUri()->getScheme(). '://'.$request->getUri()->getHost().'/';
                return $response->withJson($producto->Get($args['id'],$base_url.'productos/html')->result );
            }else{
                $respuesta = array('code'=>1, 'mesagge'=>'Se debe enviar un di de producto');
                return $response->withJson($respuesta);
            }
        });
    });
});

