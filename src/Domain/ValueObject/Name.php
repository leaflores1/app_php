<?php

namespace Leandro\AppPhp\Domain\ValueObject;

use InvalidArgumentException;

final class Name
{
    private string $value;

    public function __construct(string $value)
    {
        $trimmed = trim($value);
        if (strlen($trimmed) < 2) {
            throw new InvalidArgumentException("El nombre debe tener al menos 2 caracteres.");
        }
        $this->value = $trimmed;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
