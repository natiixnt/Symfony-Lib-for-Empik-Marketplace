<?php

declare(strict_types=1);

namespace Empik\Marketplace\Response;

final class ResponseStatus
{
    public const SUCCESS = 'success';
    public const ERROR = 'error';
    public const WARNING = 'warning';

    private function __construct()
    {
    }
}
