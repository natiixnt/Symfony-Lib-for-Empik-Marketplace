<?php

declare(strict_types=1);

namespace Empik\Marketplace\Dto;

use DateTimeImmutable;

class Order
{
    private string $id;
    private string $status;
    private string $marketplace;
    private string $amountNet;
    private string $amountGross;
    private string $amountNetPln;
    private string $amountGrossPln;
    private string $currency;
    private DateTimeImmutable $boughtAt;
    private ?string $messageToSeller;
    private Buyer $buyer;
    private Delivery $delivery;
    private InvoiceData $invoice;
    /** @var array<int, Product> */
    private array $products;
    /** @var array<int, Payment> */
    private array $payments;
    private int $revision;
    private DateTimeImmutable $updatedAt;

    /**
     * @param array<int, Product> $products
     * @param array<int, Payment> $payments
     */
    public function __construct(
        string $id,
        string $status,
        string $marketplace,
        string $amountNet,
        string $amountGross,
        string $amountNetPln,
        string $amountGrossPln,
        string $currency,
        DateTimeImmutable $boughtAt,
        ?string $messageToSeller,
        Buyer $buyer,
        Delivery $delivery,
        InvoiceData $invoice,
        array $products,
        array $payments,
        int $revision,
        DateTimeImmutable $updatedAt
    ) {
        $this->id = $id;
        $this->status = $status;
        $this->marketplace = $marketplace;
        $this->amountNet = $amountNet;
        $this->amountGross = $amountGross;
        $this->amountNetPln = $amountNetPln;
        $this->amountGrossPln = $amountGrossPln;
        $this->currency = $currency;
        $this->boughtAt = $boughtAt;
        $this->messageToSeller = $messageToSeller;
        $this->buyer = $buyer;
        $this->delivery = $delivery;
        $this->invoice = $invoice;
        $this->products = $products;
        $this->payments = $payments;
        $this->revision = $revision;
        $this->updatedAt = $updatedAt;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getMarketplace(): string
    {
        return $this->marketplace;
    }

    public function getAmountNet(): string
    {
        return $this->amountNet;
    }

    public function getAmountGross(): string
    {
        return $this->amountGross;
    }

    public function getAmountNetPln(): string
    {
        return $this->amountNetPln;
    }

    public function getAmountGrossPln(): string
    {
        return $this->amountGrossPln;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getBoughtAt(): DateTimeImmutable
    {
        return $this->boughtAt;
    }

    public function getMessageToSeller(): ?string
    {
        return $this->messageToSeller;
    }

    public function getBuyer(): Buyer
    {
        return $this->buyer;
    }

    public function getDelivery(): Delivery
    {
        return $this->delivery;
    }

    public function getInvoice(): InvoiceData
    {
        return $this->invoice;
    }

    /**
     * @return array<int, Product>
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    /**
     * @return array<int, Payment>
     */
    public function getPayments(): array
    {
        return $this->payments;
    }

    public function getRevision(): int
    {
        return $this->revision;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public static function fromArray(array $data): self
    {
        $products = [];
        foreach ($data['products'] ?? [] as $product) {
            $products[] = Product::fromArray($product);
        }

        $payments = [];
        foreach ($data['payments'] ?? [] as $payment) {
            $payments[] = Payment::fromArray($payment);
        }

        return new self(
            (string) ($data['id'] ?? ''),
            (string) ($data['status'] ?? ''),
            (string) ($data['marketplace'] ?? ''),
            (string) ($data['amount_net'] ?? ''),
            (string) ($data['amount_gross'] ?? ''),
            (string) ($data['amount_net_pln'] ?? ''),
            (string) ($data['amount_gross_pln'] ?? ''),
            (string) ($data['currency'] ?? ''),
            new DateTimeImmutable($data['bought_at'] ?? 'now'),
            $data['message_to_seller'] ?? null,
            Buyer::fromArray($data['buyer'] ?? []),
            Delivery::fromArray($data['delivery'] ?? []),
            InvoiceData::fromArray($data['invoice'] ?? []),
            $products,
            $payments,
            isset($data['revision']) ? (int) $data['revision'] : 0,
            new DateTimeImmutable($data['updated_at'] ?? 'now')
        );
    }
}
