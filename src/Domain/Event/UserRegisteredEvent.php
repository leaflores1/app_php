<?php

namespace Leandro\AppPhp\Domain\Event;

use Leandro\AppPhp\Domain\Entity\User;

class UserRegisteredEvent
{
    public function dispatch(User $user): void
    {
        // Lógica mínima: en un sistema real, se notificaría a un event bus o se llamaría a los listeners.
        // O se podría inyectar un "mailer" para enviar un correo.
    }
}
