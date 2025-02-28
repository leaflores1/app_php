<?php

namespace Leandro\AppPhp\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;
use Leandro\AppPhp\Domain\ValueObject\UserId;
use Leandro\AppPhp\Domain\ValueObject\Name;
use Leandro\AppPhp\Domain\ValueObject\Email;
use Leandro\AppPhp\Domain\ValueObject\Password;
use DateTimeImmutable;

#[ORM\Entity]
#[ORM\Table(name: "users")]
class User
{
    #[ORM\Id]
    #[ORM\Column(type: "string", length: 36, unique: true)]
    private string $id;

    #[ORM\Column(type: "string", length: 100)]
    private string $name;

    #[ORM\Column(type: "string", length: 150, unique: true)]
    private string $email;

    #[ORM\Column(type: "string", length: 255)]
    private string $password;

    #[ORM\Column(type: "datetime_immutable")]
    private DateTimeImmutable $createdAt;

    public function __construct(
        UserId $id,
        Name $name,
        Email $email,
        Password $password
    ) {
        $this->id = (string) $id;
        $this->name = (string) $name;
        $this->email = (string) $email;
        $this->password = (string) $password;
        $this->createdAt = new DateTimeImmutable();
    }

    public function id(): UserId
    {
        return new UserId($this->id);
    }

    public function name(): Name
    {
        return new Name($this->name);
    }

    public function email(): Email
    {
        return new Email($this->email);
    }

    public function password(): Password
    {
        return new Password($this->password, true); 
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
