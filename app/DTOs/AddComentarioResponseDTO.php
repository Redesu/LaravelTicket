<?php
namespace App\DTOs;

class AddComentarioResponseDTO
{
    private ?bool $success;
    private ?string $comentario;
    private ?array $usuario;
    private ?string $created_at;
    private ?string $message;
    private ?string $error;
    public function __construct(
        bool $success,
        string $comentario,
        array $usuario,
        string $created_at,
        string $message,
        string $error
    ) {
        $this->success = $success;
        $this->comentario = $comentario;
        $this->usuario = $usuario;
        $this->created_at = $created_at;
        $this->message = $message;
        $this->error = $error;
    }

    public static function error(string $message, string $error): self
    {
        return new self(
            success: false,
            message: $message,
            error: $error
        );
    }





}