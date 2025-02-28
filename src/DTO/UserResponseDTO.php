<?php

namespace Leandro\AppPhp\DTO;

class UserResponseDTO
{
    public bool $success;
    public string $message;

    public function __construct(bool $success, string $message)
    {
        $this->success = $success;
        $this->message = $message;
    }
}
