<?php

declare(strict_types=1);

namespace Empik\Marketplace\Tests\Unit;

use DateTimeImmutable;
use Empik\Marketplace\Client\EmpikHttpClientInterface;
use Empik\Marketplace\Exception\EmpikApiException;
use Empik\Marketplace\Exception\EmpikValidationException;
use Empik\Marketplace\Service\OrdersService;
use PHPUnit\Framework\TestCase;

class OrdersServiceTest extends TestCase
{
    public function testChangeStatusRequiresTrackingNumberForSent(): void
    {
        $client = new StubHttpClient([['status' => 'success', 'errors' => []]]);
        $service = new OrdersService($client);

        $this->expectException(EmpikValidationException::class);
        $service->changeStatus('123', 'SENT');
    }

    public function testGetOrdersThrowsOnErrorStatus(): void
    {
        $client = new StubHttpClient([[
            'status' => 'error',
            'data' => [],
            'errors' => [['code' => 'E001', 'message' => 'Invalid range']],
        ]]);

        $service = new OrdersService($client);

        $this->expectException(EmpikApiException::class);
        $service->getOrders(new DateTimeImmutable('2023-01-01'), new DateTimeImmutable('2023-02-01'));
    }

    public function testGetOrderMapsDto(): void
    {
        $payload = [
            'status' => 'success',
            'data' => [
                'id' => 'ORD-1',
                'status' => 'NEW',
                'marketplace' => 'empik',
                'amount_net' => '100.00',
                'amount_gross' => '123.00',
                'amount_net_pln' => '100.00',
                'amount_gross_pln' => '123.00',
                'currency' => 'PLN',
                'bought_at' => '2023-01-10T10:00:00+00:00',
                'message_to_seller' => 'Please deliver fast',
                'buyer' => ['id' => 'B1', 'email' => 'buyer@example.com'],
                'delivery' => [
                    'email' => 'buyer@example.com',
                    'phone_number' => '123456789',
                    'login' => 'buyer',
                    'method' => 'courier',
                    'method_id' => 'c1',
                    'amount_gross' => '10.00',
                    'amount_net' => '8.00',
                    'address_line1' => 'Line 1',
                    'address_line2' => 'Line 2',
                    'street' => 'Main',
                    'building_number' => '1',
                    'flat_number' => '2',
                    'city' => 'City',
                    'postal_code' => '00-000',
                    'province' => 'MA',
                    'country_code' => 'PL',
                    'point_id' => null,
                    'point_name' => null,
                    'language' => 'pl',
                    'smart' => true,
                    'calculated_number_of_packages' => 1,
                ],
                'invoice' => [
                    'want_invoice' => true,
                    'company' => 'ACME',
                    'full_name' => 'ACME Sp. z o.o.',
                    'address' => 'Street 1',
                    'country_code' => 'PL',
                    'nip' => '1234567890',
                ],
                'products' => [[
                    'order_product_id' => 'OP1',
                    'sku' => 'SKU1',
                    'offer_id' => 'OF1',
                    'name' => 'Product 1',
                    'amount_gross' => '123.00',
                    'total_amount_net' => '100.00',
                    'vat_rate' => '23',
                    'quantity' => 1,
                    'voucher_amount' => null,
                ]],
                'payments' => [[
                    'id' => 'PAY1',
                    'type' => 'card',
                    'provider' => 'payu',
                    'finished_at' => '2023-01-10T10:05:00+00:00',
                    'amount_gross' => '123.00',
                    'amount_net' => '100.00',
                ]],
                'revision' => 1,
                'updated_at' => '2023-01-10T10:05:00+00:00',
            ],
            'errors' => [],
        ];

        $client = new StubHttpClient([$payload]);
        $service = new OrdersService($client);

        $response = $service->getOrder('ORD-1');

        self::assertSame('success', $response->getStatus());
        self::assertSame('ORD-1', $response->getData()->getId());
        self::assertSame('buyer@example.com', $response->getData()->getBuyer()->getEmail());
        self::assertSame('Product 1', $response->getData()->getProducts()[0]->getName());
        self::assertSame('payu', $response->getData()->getPayments()[0]->getProvider());
    }
}

class StubHttpClient implements EmpikHttpClientInterface
{
    /** @var array<int, array<string, mixed>> */
    private array $responses;

    /** @var array<string, mixed> */
    public array $lastOptions = [];

    public function __construct(array $responses)
    {
        $this->responses = $responses;
    }

    public function request(string $method, string $uri, array $options = []): array
    {
        $this->lastOptions = ['method' => $method, 'uri' => $uri, 'options' => $options];

        if (empty($this->responses)) {
            return [];
        }

        return array_shift($this->responses);
    }
}
