<?php

use Slim\Http\Request;
use Slim\Http\Response;
use App\Lib\ApiResponse;
use Firebase\JWT\JWT;
use Tuupola\Base62;

$app->group('/api', function() use($app){
    $app->group('/v1', function ($app){
        $app->group('/user', function ($app){
            $app->post('/login', function (Request $request, Response $response) {
                $rta = new ApiResponse();
                if($request->getContentType() !='application/json') {
                    $rta->setCodigo(12);
                    $rta->setRespuesta(false);
                }else{
                    $datos = $request->getParsedBody();
                    $user = (isset($datos['usuario']))?$datos['usuario']:false;
                    $pass = (isset($datos['password']))?$datos['password']:false;
                    if($user && $pass){
                        $usuarios = new \Entidad\Usuario_model();
                        $usuario=$usuarios->loginUsuario($user,$pass);
                        if($usuario){
                            if($usuario->estado == 1){
                                if($usuarios->decodePass( $usuario->password,$usuario->email)===$pass){
                                    $now = new DateTime();
                                    $future = new DateTime("now +2 hours");
                                    $jti = (new Base62)->encode(random_bytes(16));
                                    $payload = [
                                        "iat" => $now->getTimeStamp(),
                                        "exp" => $future->getTimeStamp(),
                                        "jti" => $usuario->id,
                                        "sub" => $usuario->nombre,
                                        "scope" => 'all'
                                    ];
                                    $secret = getenv("JWT_SECRET");
                                    $token = JWT::encode($payload, $secret, "HS256");
                                    $data["token"]   = $token;
                                    $data["expira"]  = $future->format('d-m-Y H:m:s');
                                    $data['usuario'] = $usuario->nombre;

                                    $rta->setCodigo(0);
                                    $rta->setRespuesta($data);
                                }else{
                                    $rta->setCodigo(10);
                                    $rta->setRespuesta(false);
                                }
                            }else{
                                $rta->setCodigo(9);
                                $rta->setRespuesta(false);
                            }
                        }else{
                            $rta->setCodigo(10);
                            $rta->setRespuesta(false);
                        }
                    }else{
                        $rta->setCodigo(13);
                        $rta->setRespuesta(false);
                    }
                }
                return $response->withJson($rta->getRta());
            });
            $app->post('/registrar', function (Request $request, Response $response)   {
                $rta = new ApiResponse();

                $usuario = new \Entidad\Usuario_model();
                $datos    = $request->getParsedBody();
                $email    = ($datos['email'])?$datos['email']:false;
                $nombre   = ($datos['nombre'])?$datos['nombre']:false;
                $password = ($datos['password'])?$datos['password']:false;
                $pass1    = ($datos['pass1'])?$datos['pass1']:false;
                if($email && $nombre && $password && $pass1){
                    if ($password === $pass1) {
                        $respuesta = $usuario->InsertOrUpdate($datos);
                        if($respuesta->response === true  ){
                            $rta->setCodigo(0);
                            $rta->setRespuesta($respuesta);
                        }else{
                            $rta->setCodigo(15);
                            $rta->setRespuesta(false);
                        }
                    } else {
                        $rta->setCodigo(14);
                        $rta->setRespuesta(false);
                    }
                }else{
                    $rta->setCodigo(13);
                    $rta->setRespuesta(false);
                }
                return $response->withJson($rta->getRta());
            });
            $app->post('/activar', function (Request $request, Response $response)   {
                $rta = new ApiResponse();
                $usuarios = new \Entidad\Usuario_model();
                $datos    = $request->getParsedBody();
                $id    = ($datos['id'])?$datos['id']:false;
                if($id){
                    if(is_int($id)){
                        $usuario = $usuarios->Get($id)->result;
                        if($usuario){
                            if($usuario->estado==0){
                                $respuesta = $usuarios->Activar($id);
                                $rta->setCodigo(0);
                                $rta->setRespuesta($respuesta);
                            }else{
                                $rta->setCodigo(19);
                                $rta->setRespuesta(false);
                            }
                        }else{
                            $rta->setCodigo(18);
                            $rta->setRespuesta(false);
                        }
                    }else{
                        $rta->setCodigo(17);
                        $rta->setRespuesta(false);
                    }
                }else{
                    $rta->setCodigo(13);
                    $rta->setRespuesta(false);
                }
                return $response->withJson($rta->getRta());
            });
        });
    });
});

