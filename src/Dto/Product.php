<?php

declare(strict_types=1);

namespace Empik\Marketplace\Dto;

class Product
{
    private string $orderProductId;
    private string $sku;
    private string $offerId;
    private string $name;
    private string $amountGross;
    private string $totalAmountNet;
    private string $vatRate;
    private int $quantity;
    private ?string $voucherAmount;

    public function __construct(
        string $orderProductId,
        string $sku,
        string $offerId,
        string $name,
        string $amountGross,
        string $totalAmountNet,
        string $vatRate,
        int $quantity,
        ?string $voucherAmount
    ) {
        $this->orderProductId = $orderProductId;
        $this->sku = $sku;
        $this->offerId = $offerId;
        $this->name = $name;
        $this->amountGross = $amountGross;
        $this->totalAmountNet = $totalAmountNet;
        $this->vatRate = $vatRate;
        $this->quantity = $quantity;
        $this->voucherAmount = $voucherAmount;
    }

    public function getOrderProductId(): string
    {
        return $this->orderProductId;
    }

    public function getSku(): string
    {
        return $this->sku;
    }

    public function getOfferId(): string
    {
        return $this->offerId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAmountGross(): string
    {
        return $this->amountGross;
    }

    public function getTotalAmountNet(): string
    {
        return $this->totalAmountNet;
    }

    public function getVatRate(): string
    {
        return $this->vatRate;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getVoucherAmount(): ?string
    {
        return $this->voucherAmount;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            (string) ($data['order_product_id'] ?? ''),
            (string) ($data['sku'] ?? ''),
            (string) ($data['offer_id'] ?? ''),
            (string) ($data['name'] ?? ''),
            (string) ($data['amount_gross'] ?? ''),
            (string) ($data['total_amount_net'] ?? ''),
            (string) ($data['vat_rate'] ?? ''),
            isset($data['quantity']) ? (int) $data['quantity'] : 0,
            isset($data['voucher_amount']) ? (string) $data['voucher_amount'] : null
        );
    }
}
