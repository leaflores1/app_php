<?php

namespace Leandro\AppPhp\Infrastructure\Persistence;

use Leandro\AppPhp\Domain\Entity\User;
use Leandro\AppPhp\Domain\Repository\UserRepositoryInterface;
use Leandro\AppPhp\Domain\ValueObject\UserId;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineUserRepository implements UserRepositoryInterface
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function save(User $user): void
    {
        $this->em->persist($user);
        $this->em->flush();
    }

    public function findById(UserId $id): ?User
    {
        return $this->em->getRepository(User::class)->find((string) $id);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->em->getRepository(User::class)->findOneBy(['email' => $email]);
    }

    public function delete(UserId $id): void
    {
        $user = $this->findById($id);
        if ($user) {
            $this->em->remove($user);
            $this->em->flush();
        }
    }
}
