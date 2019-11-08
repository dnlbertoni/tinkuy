<?php

//use Doctrine\Common\Annotations\AnnotationReader;
//use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\ORM\EntityManager;
//use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\Tools\Setup;

require 'vendor/autoload.php';

$app = new \Slim\App();

$paths = array(
    __DIR__ . "/../entity"
);

$isDevMode = false;
// the connection configuration
$dbParams = array(
    'driver' => 'pdo_mysql',
    'host' => '127.0.0.1',
    'user' => 'danielbertoni',
    'password' => 'CE535server',
    'dbname' => 'reclamos',
);

$configDoctrine = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode, null, null, false);

$entityManager = EntityManager::create($dbParams, $configDoctrine);