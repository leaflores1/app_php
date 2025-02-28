<?php

namespace Leandro\AppPhp\Domain\ValueObject;

use Leandro\AppPhp\Domain\Exception\InvalidEmailException;

final class Email
{
    private string $value;

    public function __construct(string $value)
    {
        $trimmed = trim($value);
        if (!filter_var($trimmed, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidEmailException("Email inválido: $value");
        }
        $this->value = $trimmed;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
