<?php

namespace App\DTOs\Common;

class ValidationResultDTO
{
    public function __construct(
        private bool $isValid,
        private ?string $message = null
    ) {
    }

    public function isValid(): bool
    {
        return $this->isValid;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

}