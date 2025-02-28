<?php

namespace Leandro\AppPhp\Domain\Repository;

use Leandro\AppPhp\Domain\Entity\User;
use Leandro\AppPhp\Domain\ValueObject\UserId;

interface UserRepositoryInterface
{
    public function save(User $user): void;
    public function findById(UserId $id): ?User;
    public function findByEmail(string $email): ?User;
    public function delete(UserId $id): void;
}
