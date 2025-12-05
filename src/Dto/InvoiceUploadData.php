<?php

declare(strict_types=1);

namespace Empik\Marketplace\Dto;

class InvoiceUploadData
{
    private string $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public static function fromArray(array $data): self
    {
        return new self((string) ($data['id'] ?? ''));
    }
}
