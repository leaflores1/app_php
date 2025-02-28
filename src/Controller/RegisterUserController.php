<?php

namespace Leandro\AppPhp\Controller;

use Leandro\AppPhp\UseCase\RegisterUserUseCase;
use Leandro\AppPhp\DTO\RegisterUserRequest;
use Leandro\AppPhp\DTO\UserResponseDTO;

class RegisterUserController
{
    private RegisterUserUseCase $useCase;

    public function __construct(RegisterUserUseCase $useCase)
    {
        $this->useCase = $useCase;
    }

    public function __invoke(array $requestData): string
    {
        // Se asume que $requestData viene de $_POST o JSON decodificado
        $registerRequest = new RegisterUserRequest(
            $requestData['name'] ?? '',
            $requestData['email'] ?? '',
            $requestData['password'] ?? ''
        );

        try {
            $this->useCase->execute($registerRequest);

            // Respuesta exitosa
            $responseDTO = new UserResponseDTO(true, "User registered successfully");
            return json_encode($responseDTO);
        } catch (\Exception $ex) {
            // Manejo de excepciones: UserAlreadyExistsException, etc.
            $responseDTO = new UserResponseDTO(false, $ex->getMessage());
            return json_encode($responseDTO);
        }
    }
}
