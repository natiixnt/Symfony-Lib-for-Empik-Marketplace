<?php

declare(strict_types=1);

namespace Empik\Marketplace\Response;

use Empik\Marketplace\Dto\Error;
use Empik\Marketplace\Dto\Order;

class OrderResponse
{
    private string $status;

    private Order $data;

    /** @var array<int, Error> */
    private array $errors;

    /**
     * @param array<int, Error> $errors
     */
    public function __construct(string $status, Order $data, array $errors = [])
    {
        $this->status = $status;
        $this->data = $data;
        $this->errors = $errors;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getData(): Order
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

    public static function fromArray(array $response): self
    {
        $errors = [];
        foreach ($response['errors'] ?? [] as $errorData) {
            $errors[] = Error::fromArray($errorData);
        }

        return new self((string) ($response['status'] ?? ''), Order::fromArray($response['data'] ?? []), $errors);
    }
}
