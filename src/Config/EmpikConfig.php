<?php

declare(strict_types=1);

namespace Empik\Marketplace\Config;

use Psr\Log\LoggerInterface;

class EmpikConfig
{
    private string $baseUrl;

    private string $apiKey;

    private float $timeout;

    private ?LoggerInterface $logger;

    public function __construct(string $baseUrl, string $apiKey, float $timeout = 10.0, ?LoggerInterface $logger = null)
    {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->apiKey = $apiKey;
        $this->timeout = $timeout;
        $this->logger = $logger;
    }

    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    public function getTimeout(): float
    {
        return $this->timeout;
    }

    public function getLogger(): ?LoggerInterface
    {
        return $this->logger;
    }
}
