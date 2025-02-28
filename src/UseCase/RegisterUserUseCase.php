<?php

namespace Leandro\AppPhp\UseCase;

use Leandro\AppPhp\Domain\Entity\User;
use Leandro\AppPhp\Domain\Event\UserRegisteredEvent;
use Leandro\AppPhp\Domain\Exception\UserAlreadyExistsException;
use Leandro\AppPhp\Domain\Repository\UserRepositoryInterface;
use Leandro\AppPhp\Domain\ValueObject\UserId;
use Leandro\AppPhp\Domain\ValueObject\Name;
use Leandro\AppPhp\Domain\ValueObject\Email;
use Leandro\AppPhp\Domain\ValueObject\Password;
use Leandro\AppPhp\DTO\RegisterUserRequest;

class RegisterUserUseCase
{
    private UserRepositoryInterface $userRepo;
    private ?UserRegisteredEvent $userRegisteredEvent;

    public function __construct(
        UserRepositoryInterface $userRepo,
        ?UserRegisteredEvent $userRegisteredEvent = null
    ) {
        $this->userRepo = $userRepo;
        $this->userRegisteredEvent = $userRegisteredEvent;
    }

    /**
     * Registra un usuario y devuelve la entidad creada.
     */
    public function execute(RegisterUserRequest $request): User
    {
        // 1. Verificar si ya existe un usuario con ese email
        $existingUser = $this->userRepo->findByEmail($request->email);
        if ($existingUser !== null) {
            throw new UserAlreadyExistsException($request->email);
        }

        // 2. Crear la entidad User
      
        $user = new User(
            new UserId(),             
            new Name($request->name),
            new Email($request->email),
            new Password($request->password)
        );

        // 3. Guardar en la base de datos
        $this->userRepo->save($user);

        // 4. Disparar evento de dominio, si existe
        if ($this->userRegisteredEvent) {
            $this->userRegisteredEvent->dispatch($user);
        }

        // 5. Devolver la entidad creada
        return $user;
    }
}
