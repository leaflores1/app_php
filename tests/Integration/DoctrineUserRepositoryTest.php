<?php

namespace Tests\Integration;

use PHPUnit\Framework\TestCase;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\DBAL\DriverManager;
use Leandro\AppPhp\Domain\Entity\User;
use Leandro\AppPhp\Domain\ValueObject\UserId;
use Leandro\AppPhp\Domain\ValueObject\Name;
use Leandro\AppPhp\Domain\ValueObject\Email;
use Leandro\AppPhp\Domain\ValueObject\Password;
use Leandro\AppPhp\Infrastructure\Persistence\DoctrineUserRepository;

class DoctrineUserRepositoryTest extends TestCase
{
    private ?EntityManager $entityManager;
    private ?DoctrineUserRepository $userRepository;

    protected function setUp(): void
    {
        $config = ORMSetup::createAttributeMetadataConfiguration([__DIR__ . '/../../src/Domain/Entity'], true);

        $dbParams = [
            'driver'   => 'pdo_mysql',
            'host'     => 'db',
            'dbname'   => 'app_php',
            'user'     => 'root',
            'password' => 'leandro1',
        ];

        $connection = DriverManager::getConnection($dbParams, $config);
        $this->entityManager = new EntityManager($connection, $config);
        $this->userRepository = new DoctrineUserRepository($this->entityManager);
    }

    public function testSaveAndFindUser()
    {
        $userId = new UserId(uniqid());
        $name = new Name("Leandro");
        $email = new Email("testuser@example.com");
        $password = new Password("SecurePassword123!");

        $user = new User($userId, $name, $email, $password);
        $this->userRepository->save($user);

        // Buscar por ID
        $foundUserById = $this->userRepository->findById($userId);
        $this->assertNotNull($foundUserById);
        $this->assertEquals($user->email()->__toString(), $foundUserById->email()->__toString());

        // Buscar por Email
        $foundUserByEmail = $this->userRepository->findByEmail("testuser@example.com");
        $this->assertNotNull($foundUserByEmail);
        $this->assertEquals($user->name()->__toString(), $foundUserByEmail->name()->__toString());
    }

    public function testDeleteUser()
    {
        $userId = new UserId(uniqid());
        $name = new Name("Leandro");
        $email = new Email("deleteuser@example.com");
        $password = new Password("SecurePassword123!");

        $user = new User($userId, $name, $email, $password);
        $this->userRepository->save($user);

        $this->userRepository->delete($userId);

        $deletedUser = $this->userRepository->findById($userId);
        $this->assertNull($deletedUser);
    }

    protected function tearDown(): void
    {
        $this->entityManager->close();
        $this->entityManager = null;
        $this->userRepository = null;
    }
}
