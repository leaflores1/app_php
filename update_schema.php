<?php

declare(strict_types=1);

use Doctrine\ORM\Tools\SchemaTool;

require_once __DIR__ . '/vendor/autoload.php';

// Ajusta la ruta a tu archivo que retorna $entityManager:
$entityManager = require __DIR__ . '/config/bootstrap_doctrine.php';

// Cargamos la metadata de todas las entidades
$metadata = $entityManager->getMetadataFactory()->getAllMetadata();

if (empty($metadata)) {
    echo "No hay metadatos de entidades. Verifica que tu carpeta de entidades y configuración estén correctas.\n";
    exit(1);
}

$schemaTool = new SchemaTool($entityManager);

try {
    // El 'true' indica que force la actualización (equivalente a --force)
    $schemaTool->updateSchema($metadata, true);
    echo "¡Esquema actualizado correctamente!\n";
} catch (\Exception $e) {
    echo "Error al actualizar el esquema: " . $e->getMessage() . "\n";
    exit(1);
}