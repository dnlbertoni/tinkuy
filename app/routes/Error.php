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

$app->get('/error/{id}', function (Request $request, Response $response,$param) use($container) {
    $errores = new \Entidad\Errores_model();
    $error = $errores->Get($param['id'])->result;
    //var_dump($error);die();
    $base_url = $request->getUri()->getScheme(). '://'.$request->getUri()->getHost().'/';
    $args = array(  'error'=>$error->id,
        'errormessage'=>$error->name);
    return $this->view->render($response, 'error.phtml', $args);
});
