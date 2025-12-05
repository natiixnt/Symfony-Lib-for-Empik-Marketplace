<?php

declare(strict_types=1);

namespace Empik\Marketplace\Dto;

class Buyer
{
    private string $id;

    private string $email;

    public function __construct(string $id, string $email)
    {
        $this->id = $id;
        $this->email = $email;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public static function fromArray(array $data): self
    {
        return new self((string) ($data['id'] ?? ''), (string) ($data['email'] ?? ''));
    }
}
