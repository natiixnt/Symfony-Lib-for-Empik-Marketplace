<?php

declare(strict_types=1);

namespace Empik\Marketplace\Response;

use Empik\Marketplace\Dto\Error;

class ChangeStatusResponse
{
    private string $status;

    /** @var array<int, Error> */
    private array $errors;

    /**
     * @param array<int, Error> $errors
     */
    public function __construct(string $status, array $errors = [])
    {
        $this->status = $status;
        $this->errors = $errors;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return array<int, Error>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    public static function fromArray(array $data): self
    {
        $errors = [];
        foreach ($data['errors'] ?? [] as $errorData) {
            $errors[] = Error::fromArray($errorData);
        }

        return new self((string) ($data['status'] ?? ''), $errors);
    }
}
