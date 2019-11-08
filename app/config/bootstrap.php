<?php

require 'vendor/autoload.php';

$app = new \Slim\App();

//Doctrine
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

$paths = array(
    __DIR__ . "/app/entity"
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