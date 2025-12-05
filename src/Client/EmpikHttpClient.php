<?php

declare(strict_types=1);

namespace Empik\Marketplace\Client;

use Empik\Marketplace\Config\EmpikConfig;
use Empik\Marketplace\Exception\EmpikHttpException;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class EmpikHttpClient implements EmpikHttpClientInterface
{
    private HttpClientInterface $httpClient;

    private EmpikConfig $config;

    private ?LoggerInterface $logger;

    public function __construct(HttpClientInterface $httpClient, EmpikConfig $config)
    {
        $this->httpClient = $httpClient;
        $this->config = $config;
        $this->logger = $config->getLogger();
    }

    /**
     * @throws EmpikHttpException
     */
    public function request(string $method, string $uri, array $options = []): array
    {
        $headers = $options['headers'] ?? [];
        $headers = array_merge([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->config->getApiKey(),
        ], $headers);

        $options['headers'] = $headers;
        $options['timeout'] = $options['timeout'] ?? $this->config->getTimeout();

        $url = $this->config->getBaseUrl() . $uri;

        $this->log('Sending request', ['method' => $method, 'url' => $url, 'options' => $this->sanitizeOptions($options)]);

        try {
            $response = $this->httpClient->request($method, $url, $options);
            return $this->parseResponse($response);
        } catch (\Throwable $exception) {
            throw new EmpikHttpException('HTTP request failed: ' . $exception->getMessage(), null, $exception);
        }
    }

    /**
     * @throws EmpikHttpException
     * @return array<string, mixed>
     */
    private function parseResponse(ResponseInterface $response): array
    {
        $statusCode = $response->getStatusCode();
        $content = $response->getContent(false); // Do not throw on non-2xx

        if ($statusCode >= 400) {
            throw new EmpikHttpException('Empik API returned HTTP error: ' . $statusCode, $statusCode);
        }

        $decoded = json_decode($content, true);

        if ($decoded === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new EmpikHttpException('Failed to decode Empik API response: ' . json_last_error_msg(), $statusCode);
        }

        if (!is_array($decoded)) {
            throw new EmpikHttpException('Unexpected response format from Empik API', $statusCode);
        }

        $this->log('Received response', ['status' => $statusCode, 'body' => $decoded]);

        return $decoded;
    }

    private function log(string $message, array $context = []): void
    {
        if ($this->logger instanceof LoggerInterface) {
            $this->logger->info('[EmpikMarketplace] ' . $message, $context);
        }
    }

    private function sanitizeOptions(array $options): array
    {
        if (isset($options['headers']['Authorization'])) {
            $options['headers']['Authorization'] = 'Bearer ***';
        }

        return $options;
    }
}
