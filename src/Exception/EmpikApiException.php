<?php

declare(strict_types=1);

namespace Empik\Marketplace\Exception;

use RuntimeException;

class EmpikApiException extends RuntimeException
{
    /** @var array<int, array{code?: string, message: string}> */
    private array $errors;

    /**
     * @param array<int, array{code?: string, message: string}> $errors
     */
    public function __construct(string $message, array $errors = [])
    {
        parent::__construct($message);
        $this->errors = $errors;
    }

    /**
     * @return array<int, array{code?: string, message: string}>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
