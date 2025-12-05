<?php

declare(strict_types=1);

namespace Empik\Marketplace\Exception;

use RuntimeException;

class EmpikHttpException extends RuntimeException
{
    private ?int $statusCode;

    public function __construct(string $message, ?int $statusCode = null, ?\Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
        $this->statusCode = $statusCode;
    }

    public function getStatusCode(): ?int
    {
        return $this->statusCode;
    }
}
