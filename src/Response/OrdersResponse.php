<?php

declare(strict_types=1);

namespace Empik\Marketplace\Response;

use Empik\Marketplace\Dto\Error;
use Empik\Marketplace\Dto\Order;

class OrdersResponse
{
    private string $status;

    /** @var array<int, Order> */
    private array $data;

    /** @var array<int, Error> */
    private array $errors;

    /**
     * @param array<int, Order> $data
     * @param array<int, Error> $errors
     */
    public function __construct(string $status, array $data, array $errors = [])
    {
        $this->status = $status;
        $this->data = $data;
        $this->errors = $errors;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return array<int, Order>
     */
    public function getData(): array
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
        $orders = [];
        foreach ($response['data'] ?? [] as $orderData) {
            $orders[] = Order::fromArray($orderData);
        }

        $errors = [];
        foreach ($response['errors'] ?? [] as $errorData) {
            $errors[] = Error::fromArray($errorData);
        }

        return new self((string) ($response['status'] ?? ''), $orders, $errors);
    }
}
