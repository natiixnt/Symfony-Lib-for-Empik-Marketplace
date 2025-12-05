<?php

declare(strict_types=1);

namespace Empik\Marketplace\Response;

use Empik\Marketplace\Dto\Error;
use Empik\Marketplace\Dto\InvoiceUploadData;

class AddInvoiceResponse
{
    private string $status;

    private InvoiceUploadData $data;

    /** @var array<int, Error> */
    private array $errors;

    /**
     * @param array<int, Error> $errors
     */
    public function __construct(string $status, InvoiceUploadData $data, array $errors = [])
    {
        $this->status = $status;
        $this->data = $data;
        $this->errors = $errors;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getData(): InvoiceUploadData
    {
        return $this->data;
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

        return new self((string) ($data['status'] ?? ''), InvoiceUploadData::fromArray($data['data'] ?? []), $errors);
    }
}
