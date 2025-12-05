<?php

declare(strict_types=1);

namespace Empik\Marketplace\Tests\Integration;

use Empik\Marketplace\Client\EmpikHttpClient;
use Empik\Marketplace\Config\EmpikConfig;
use Empik\Marketplace\Service\OrdersService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\ResponseInterface;

class OrdersServiceIntegrationTest extends TestCase
{
    public function testGetOrdersReturnsCollection(): void
    {
        $payload = [
            'status' => 'success',
            'data' => [[
                'id' => 'ORD-10',
                'status' => 'NEW',
                'marketplace' => 'empik',
                'amount_net' => '100.00',
                'amount_gross' => '123.00',
                'amount_net_pln' => '100.00',
                'amount_gross_pln' => '123.00',
                'currency' => 'PLN',
                'bought_at' => '2023-01-10T10:00:00+00:00',
                'message_to_seller' => null,
                'buyer' => ['id' => 'B1', 'email' => 'buyer@example.com'],
                'delivery' => [],
                'invoice' => ['want_invoice' => false],
                'products' => [],
                'payments' => [],
                'revision' => 1,
                'updated_at' => '2023-01-10T10:05:00+00:00',
            ]],
            'errors' => [],
        ];

        $callback = function (string $method, string $url, array $options) use ($payload): ResponseInterface {
            $this->assertSame('GET', $method);
            $this->assertStringContainsString('/orders', $url);
            $query = [];
            parse_str(parse_url($url, PHP_URL_QUERY) ?? '', $query);
            $this->assertArrayHasKey('date_from', $query);
            $this->assertArrayHasKey('date_to', $query);

            return new MockResponse(json_encode($payload), ['http_code' => 200]);
        };

        $mockClient = new MockHttpClient($callback);
        $config = new EmpikConfig('https://api.example.com', 'token');
        $client = new EmpikHttpClient($mockClient, $config);
        $service = new OrdersService($client);

        $response = $service->getOrders('2023-01-01', '2023-01-02');

        self::assertCount(1, $response->getData());
        self::assertSame('ORD-10', $response->getData()[0]->getId());
    }

    public function testChangeStatusSendsJsonBody(): void
    {
        $payload = [
            'status' => 'success',
            'errors' => [],
        ];

        $callback = function (string $method, string $url, array $options) use ($payload): ResponseInterface {
            $this->assertSame('POST', $method);
            $this->assertStringContainsString('/orders/ORD-2/status', $url);
            $this->assertArrayHasKey('json', $options);
            $this->assertSame('SENT', $options['json']['status']);
            $this->assertSame('TRACK-1', $options['json']['tracking_number']);

            return new MockResponse(json_encode($payload), ['http_code' => 200]);
        };

        $mockClient = new MockHttpClient($callback);
        $config = new EmpikConfig('https://api.example.com', 'token');
        $client = new EmpikHttpClient($mockClient, $config);
        $service = new OrdersService($client);

        $response = $service->changeStatus('ORD-2', 'SENT', 'TRACK-1');

        self::assertSame('success', $response->getStatus());
    }

    public function testAddInvoiceReturnsUploadId(): void
    {
        $tmpFile = tempnam(sys_get_temp_dir(), 'invoice_');
        file_put_contents($tmpFile, 'dummy pdf content');

        $payload = [
            'status' => 'success',
            'data' => ['id' => 'INV-1'],
            'errors' => [],
        ];

        $callback = function (string $method, string $url, array $options) use ($payload): ResponseInterface {
            $this->assertSame('POST', $method);
            $this->assertStringContainsString('/orders/ORD-55/invoice', $url);
            $this->assertArrayHasKey('body', $options);
            $this->assertArrayHasKey('headers', $options);

            return new MockResponse(json_encode($payload), ['http_code' => 200]);
        };

        $mockClient = new MockHttpClient($callback);
        $config = new EmpikConfig('https://api.example.com', 'token');
        $client = new EmpikHttpClient($mockClient, $config);
        $service = new OrdersService($client);

        $response = $service->addInvoice('ORD-55', $tmpFile, 'faktura vat');

        self::assertSame('INV-1', $response->getData()->getId());

        @unlink($tmpFile);
    }
}
