<?php

namespace Leandro\AppPhp\Domain\Exception;

use Exception;

class WeakPasswordException extends Exception
{
    public function __construct()
    {
        parent::__construct("La contraseña es demasiado débil. Debe contener al menos 8 caracteres, una mayúscula, un número y un símbolo.");
    }
}
