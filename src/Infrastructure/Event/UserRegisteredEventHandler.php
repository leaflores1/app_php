<?php

namespace Leandro\AppPhp\Infrastructure\Event;

use Leandro\AppPhp\Domain\Entity\User;

class UserRegisteredEventHandler
{
    public function onUserRegistered(User $user)
    {
        // Simular envío de email de bienvenida
        // Ej. $this->mailer->send("Bienvenido " . $user->name());
    }
}
