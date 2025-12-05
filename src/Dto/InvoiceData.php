<?php

declare(strict_types=1);

namespace Empik\Marketplace\Dto;

class InvoiceData
{
    private bool $wantInvoice;
    private ?string $company;
    private ?string $fullName;
    private ?string $address;
    private ?string $countryCode;
    private ?string $nip;

    public function __construct(bool $wantInvoice, ?string $company, ?string $fullName, ?string $address, ?string $countryCode, ?string $nip)
    {
        $this->wantInvoice = $wantInvoice;
        $this->company = $company;
        $this->fullName = $fullName;
        $this->address = $address;
        $this->countryCode = $countryCode;
        $this->nip = $nip;
    }

    public function wantInvoice(): bool
    {
        return $this->wantInvoice;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function getNip(): ?string
    {
        return $this->nip;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            isset($data['want_invoice']) ? (bool) $data['want_invoice'] : false,
            $data['company'] ?? null,
            $data['full_name'] ?? null,
            $data['address'] ?? null,
            $data['country_code'] ?? null,
            $data['nip'] ?? null
        );
    }
}
