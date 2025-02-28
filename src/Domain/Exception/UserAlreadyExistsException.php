<?php

namespace Leandro\AppPhp\Domain\Exception;

use Exception;

class UserAlreadyExistsException extends Exception
{
    public function __construct(string $email)
    {
        parent::__construct("Ya existe un usuario con el email: $email");
    }
}
