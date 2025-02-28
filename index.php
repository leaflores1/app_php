<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\DriverManager;

use Leandro\AppPhp\Domain\ValueObject\Name;
use Leandro\AppPhp\Domain\ValueObject\Email;
use Leandro\AppPhp\Domain\ValueObject\Password;
use Leandro\AppPhp\Domain\ValueObject\UserId;
use Leandro\AppPhp\Infrastructure\Persistence\DoctrineUserRepository;
use Leandro\AppPhp\UseCase\RegisterUserUseCase;
use Leandro\AppPhp\DTO\RegisterUserRequest;

// Handle GET request (e.g., for documentation or homepage)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    http_response_code(200);
    echo json_encode(['message' => 'Welcome to the API. Use POST to register a user with name, email, and password.']);
    exit;
}

// Asegurar que la petición sea POST para registration
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido, usa POST.']);
    exit;
}

// Tomar datos de la petición POST en JSON
$rawData = file_get_contents('php://input');
$decoded = json_decode($rawData, true);

// Validar si el JSON está bien formado
if (!is_array($decoded)) {
    http_response_code(400);
    echo json_encode(['error' => 'JSON mal formado o vacío.']);
    exit;
}

// Verificar si existen los campos requeridos
if (!isset($decoded['name'], $decoded['email'], $decoded['password'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Faltan datos: name, email o password.']);
    exit;
}

$nameValue = trim($decoded['name']);
$emailValue = trim($decoded['email']);
$passwordValue = trim($decoded['password']);

// Verificar que los datos no estén vacíos
if ($nameValue === '' || $emailValue === '' || $passwordValue === '') {
    http_response_code(400);
    echo json_encode(['error' => 'Ningún campo puede estar vacío.']);
    exit;
}

// 1) CONFIGURACIÓN DE DOCTRINE ORM
$paths = [__DIR__ . '/src/Domain/Entity'];
$isDevMode = true;

$config = ORMSetup::createAttributeMetadataConfiguration($paths, $isDevMode);

$dbParams = [
    'driver'   => 'pdo_mysql',
    'host'     => 'db',
    'dbname'   => 'app_php',
    'user'     => 'root',
    'password' => 'leandro1',
];

$connection = DriverManager::getConnection($dbParams, $config);
$entityManager = new EntityManager($connection, $config);

// 2) Crear instancia de objetos de valor
try {
    $name = new Name($nameValue);
    $email = new Email($emailValue);
    $password = new Password($passwordValue);
    $userId = new UserId(uniqid('user_', true)); // Generar un ID único

    // 3) Construir el DTO
    $dto = new RegisterUserRequest(
        $name->__toString(),
        $email->__toString(),
        $password->__toString()
    );

    // 4) Crear el repositorio de usuarios
    $userRepository = new DoctrineUserRepository($entityManager);

    // 5) Caso de uso para registrar usuario
    $registerUserUseCase = new RegisterUserUseCase($userRepository, null);

    // 6) Ejecutar el caso de uso
    $user = $registerUserUseCase->execute($dto);

    // 7) Responder con JSON
    http_response_code(201);
    echo json_encode([
        'message' => 'Usuario registrado exitosamente',
        'user' => [
            'name' => $user->name()->__toString(),
            'email' => $user->email()->__toString(),
            'id' => $user->id()->__toString(),
        ],
    ], JSON_PRETTY_PRINT);
} catch (\Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
}