<?php

use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\DriverManager;

$paths = [__DIR__ . '/../src/Domain/Entity'];
$isDevMode = true;

// Configuración de la base de datos 
$dbParams = [
    'driver'   => 'pdo_mysql',  
    'host'     => 'db',
    'dbname'   => 'app_php',  
    'user'     => 'root',
    'password' => 'leandro1',
];

//configurar Doctrine con ORMSetup 
$config = ORMSetup::createAttributeMetadataConfiguration($paths, $isDevMode);

// Crear conexión con la base de datos
$connection = DriverManager::getConnection($dbParams, $config);

// Crear EntityManager
$entityManager = new EntityManager($connection, $config);

return $entityManager;
