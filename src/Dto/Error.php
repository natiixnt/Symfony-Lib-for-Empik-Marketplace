<?php

declare(strict_types=1);

namespace Empik\Marketplace\Dto;

class Error
{
    private ?string $code;

    private string $message;

    public function __construct(?string $code, string $message)
    {
        $this->code = $code;
        $this->message = $message;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public static function fromArray(array $data): self
    {
        return new self($data['code'] ?? null, $data['message'] ?? '');
    }
}
