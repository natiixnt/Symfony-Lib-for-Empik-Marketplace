<?php

declare(strict_types=1);

namespace Empik\Marketplace\Dto;

class Delivery
{
    private ?string $email;
    private ?string $phoneNumber;
    private ?string $login;
    private ?string $method;
    private ?string $methodId;
    private ?string $amountGross;
    private ?string $amountNet;
    private ?string $addressLine1;
    private ?string $addressLine2;
    private ?string $street;
    private ?string $buildingNumber;
    private ?string $flatNumber;
    private ?string $city;
    private ?string $postalCode;
    private ?string $province;
    private ?string $countryCode;
    private ?string $pointId;
    private ?string $pointName;
    private ?string $language;
    private ?bool $smart;
    private ?int $calculatedNumberOfPackages;

    public function __construct(
        ?string $email,
        ?string $phoneNumber,
        ?string $login,
        ?string $method,
        ?string $methodId,
        ?string $amountGross,
        ?string $amountNet,
        ?string $addressLine1,
        ?string $addressLine2,
        ?string $street,
        ?string $buildingNumber,
        ?string $flatNumber,
        ?string $city,
        ?string $postalCode,
        ?string $province,
        ?string $countryCode,
        ?string $pointId,
        ?string $pointName,
        ?string $language,
        ?bool $smart,
        ?int $calculatedNumberOfPackages
    ) {
        $this->email = $email;
        $this->phoneNumber = $phoneNumber;
        $this->login = $login;
        $this->method = $method;
        $this->methodId = $methodId;
        $this->amountGross = $amountGross;
        $this->amountNet = $amountNet;
        $this->addressLine1 = $addressLine1;
        $this->addressLine2 = $addressLine2;
        $this->street = $street;
        $this->buildingNumber = $buildingNumber;
        $this->flatNumber = $flatNumber;
        $this->city = $city;
        $this->postalCode = $postalCode;
        $this->province = $province;
        $this->countryCode = $countryCode;
        $this->pointId = $pointId;
        $this->pointName = $pointName;
        $this->language = $language;
        $this->smart = $smart;
        $this->calculatedNumberOfPackages = $calculatedNumberOfPackages;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function getMethod(): ?string
    {
        return $this->method;
    }

    public function getMethodId(): ?string
    {
        return $this->methodId;
    }

    public function getAmountGross(): ?string
    {
        return $this->amountGross;
    }

    public function getAmountNet(): ?string
    {
        return $this->amountNet;
    }

    public function getAddressLine1(): ?string
    {
        return $this->addressLine1;
    }

    public function getAddressLine2(): ?string
    {
        return $this->addressLine2;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function getBuildingNumber(): ?string
    {
        return $this->buildingNumber;
    }

    public function getFlatNumber(): ?string
    {
        return $this->flatNumber;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function getProvince(): ?string
    {
        return $this->province;
    }

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function getPointId(): ?string
    {
        return $this->pointId;
    }

    public function getPointName(): ?string
    {
        return $this->pointName;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function isSmart(): ?bool
    {
        return $this->smart;
    }

    public function getCalculatedNumberOfPackages(): ?int
    {
        return $this->calculatedNumberOfPackages;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['email'] ?? null,
            $data['phone_number'] ?? null,
            $data['login'] ?? null,
            $data['method'] ?? null,
            $data['method_id'] ?? null,
            isset($data['amount_gross']) ? (string) $data['amount_gross'] : null,
            isset($data['amount_net']) ? (string) $data['amount_net'] : null,
            $data['address_line1'] ?? null,
            $data['address_line2'] ?? null,
            $data['street'] ?? null,
            $data['building_number'] ?? null,
            $data['flat_number'] ?? null,
            $data['city'] ?? null,
            $data['postal_code'] ?? null,
            $data['province'] ?? null,
            $data['country_code'] ?? null,
            $data['point_id'] ?? null,
            $data['point_name'] ?? null,
            $data['language'] ?? null,
            isset($data['smart']) ? (bool) $data['smart'] : null,
            isset($data['calculated_number_of_packages']) ? (int) $data['calculated_number_of_packages'] : null
        );
    }
}
