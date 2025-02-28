<?php

use PHPUnit\Framework\TestCase;
use Leandro\AppPhp\Domain\Entity\User;
use Leandro\AppPhp\Domain\ValueObject\UserId;
use Leandro\AppPhp\Domain\ValueObject\Name;
use Leandro\AppPhp\Domain\ValueObject\Email;
use Leandro\AppPhp\Domain\ValueObject\Password;

class UserTest extends TestCase
{
    public function testUserCreation()
    {
        $userId = new UserId(uniqid());
        $name = new Name('Leandro');
        $email = new Email('lea312full@gmail.com');
        $password = new Password('SecurePassword123!');

        $user = new User($userId, $name, $email, $password);

        $this->assertEquals('Leandro', $user->name()->__toString());
        $this->assertEquals('lea312full@gmail.com', $user->email()->__toString());
    }
}
