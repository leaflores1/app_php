<?php

namespace Leandro\AppPhp\Domain\ValueObject;

use Leandro\AppPhp\Domain\Exception\WeakPasswordException;

final class Password
{
    private string $hashedValue;

    /**
     * @param bool $alreadyHashed Indica si la contraseña ya viene hasheada de la BD
     */
    public function __construct(string $plainOrHashed, bool $alreadyHashed = false)
    {
        if (!$alreadyHashed) {
            // Validar fortaleza
            $this->assertIsStrong($plainOrHashed);

            // Hashear
            $hashed = password_hash($plainOrHashed, PASSWORD_DEFAULT);
            if (!$hashed) {
                throw new \RuntimeException("No se pudo hashear la contraseña");
            }
            $this->hashedValue = $hashed;
        } else {
            // Se asume que ya está hasheada
            $this->hashedValue = $plainOrHashed;
        }
    }

    private function assertIsStrong(string $plain): void
    {
        // Al menos 8 caracteres, 1 mayúscula, 1 dígito, 1 carácter especial
        if (strlen($plain) < 8 ||
            !preg_match('/[A-Z]/', $plain) ||
            !preg_match('/[0-9]/', $plain) ||
            !preg_match('/[\W]/', $plain)
        ) {
            throw new WeakPasswordException("La contraseña no cumple los requisitos de seguridad.");
        }
    }

    public function __toString(): string
    {
        return $this->hashedValue;
    }

    public function verify(string $plain): bool
    {
        return password_verify($plain, $this->hashedValue);
    }
}
