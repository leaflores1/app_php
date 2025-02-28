<?php

namespace Leandro\AppPhp\Domain\ValueObject;

final class UserId
{
    private string $value;

    public function __construct(?string $value = null)
    {
        $this->value = $value ?? $this->generateId();
    }

    private function generateId(): string
    {
        return uniqid('user_', true); // Genera un ID único
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
