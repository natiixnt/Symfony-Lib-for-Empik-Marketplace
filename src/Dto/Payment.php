<?php

declare(strict_types=1);

namespace Empik\Marketplace\Dto;

use DateTimeImmutable;

class Payment
{
    private string $id;
    private string $type;
    private string $provider;
    private DateTimeImmutable $finishedAt;
    private string $amountGross;
    private string $amountNet;

    public function __construct(string $id, string $type, string $provider, DateTimeImmutable $finishedAt, string $amountGross, string $amountNet)
    {
        $this->id = $id;
        $this->type = $type;
        $this->provider = $provider;
        $this->finishedAt = $finishedAt;
        $this->amountGross = $amountGross;
        $this->amountNet = $amountNet;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getProvider(): string
    {
        return $this->provider;
    }

    public function getFinishedAt(): DateTimeImmutable
    {
        return $this->finishedAt;
    }

    public function getAmountGross(): string
    {
        return $this->amountGross;
    }

    public function getAmountNet(): string
    {
        return $this->amountNet;
    }

    public static function fromArray(array $data): self
    {
        $finishedAt = new DateTimeImmutable($data['finished_at'] ?? 'now');

        return new self(
            (string) ($data['id'] ?? ''),
            (string) ($data['type'] ?? ''),
            (string) ($data['provider'] ?? ''),
            $finishedAt,
            (string) ($data['amount_gross'] ?? ''),
            (string) ($data['amount_net'] ?? '')
        );
    }
}
