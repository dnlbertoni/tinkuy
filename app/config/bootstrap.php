<?php

//use Doctrine\Common\Annotations\AnnotationReader;
//use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\ORM\EntityManager;
//use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\Tools\Setup;

require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::create(__DIR__.'/../../public/');
$dotenv->load();

$app = new \Slim\App();

$paths = array(
    __DIR__ . "/../entity"
);

$isDevMode = false;
// the connection configuration
$dbParams = array(
    'driver' => 'pdo_mysql',
    'host' => getenv(DB_HOST),
    'user' => getenv(DB_USER),
    'password' => getenv(DB_PASS),
    'dbname' => getenv(DB_NAME),
);

$configDoctrine = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode, null, null, false);

$entityManager = EntityManager::create($dbParams, $configDoctrine);