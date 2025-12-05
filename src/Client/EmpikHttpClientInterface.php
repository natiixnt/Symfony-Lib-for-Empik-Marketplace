<?php

declare(strict_types=1);

namespace Empik\Marketplace\Client;

interface EmpikHttpClientInterface
{
    /**
     * @return array<string, mixed>
     */
    public function request(string $method, string $uri, array $options = []): array;
}
