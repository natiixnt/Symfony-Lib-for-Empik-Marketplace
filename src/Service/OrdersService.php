<?php

declare(strict_types=1);

namespace Empik\Marketplace\Service;

use DateTimeImmutable;
use DateTimeInterface;
use Empik\Marketplace\Client\EmpikHttpClientInterface;
use Empik\Marketplace\Dto\Error as ErrorDto;
use Empik\Marketplace\Exception\EmpikApiException;
use Empik\Marketplace\Exception\EmpikHttpException;
use Empik\Marketplace\Exception\EmpikValidationException;
use Empik\Marketplace\Response\AddInvoiceResponse;
use Empik\Marketplace\Response\ChangeStatusResponse;
use Empik\Marketplace\Response\OrderResponse;
use Empik\Marketplace\Response\OrdersResponse;
use Empik\Marketplace\Response\ResponseStatus;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\Multipart\FormDataPart;

class OrdersService
{
    private EmpikHttpClientInterface $httpClient;

    public function __construct(EmpikHttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @return OrdersResponse
     * @throws EmpikApiException|EmpikHttpException|EmpikValidationException
     */
    public function getOrders(DateTimeInterface|string $dateFrom, DateTimeInterface|string $dateTo): OrdersResponse
    {
        $from = $this->normalizeDate($dateFrom);
        $to = $this->normalizeDate($dateTo);

        $payload = $this->httpClient->request('GET', '/orders', [
            'query' => [
                'date_from' => $from->format(DateTimeInterface::ATOM),
                'date_to' => $to->format(DateTimeInterface::ATOM),
            ],
        ]);

        $response = OrdersResponse::fromArray($payload);
        $this->guardStatus($response->getStatus(), $response->getErrors());

        return $response;
    }

    /**
     * @throws EmpikApiException|EmpikHttpException|EmpikValidationException
     */
    public function getOrder(string $orderId): OrderResponse
    {
        if ($orderId === '') {
            throw new EmpikValidationException('Order id cannot be empty');
        }

        $payload = $this->httpClient->request('GET', '/orders/' . urlencode($orderId));

        $response = OrderResponse::fromArray($payload);
        $this->guardStatus($response->getStatus(), $response->getErrors());

        return $response;
    }

    /**
     * @throws EmpikApiException|EmpikHttpException|EmpikValidationException
     */
    public function changeStatus(string $id, string $status, ?string $trackingNumber = null): ChangeStatusResponse
    {
        if ($id === '') {
            throw new EmpikValidationException('Order id cannot be empty');
        }

        if ($status === '') {
            throw new EmpikValidationException('Status cannot be empty');
        }

        if (strtoupper($status) === 'SENT' && ($trackingNumber === null || $trackingNumber === '')) {
            throw new EmpikValidationException('Tracking number is required for SENT status');
        }

        $body = [
            'id' => $id,
            'status' => $status,
        ];

        if ($trackingNumber !== null) {
            $body['tracking_number'] = $trackingNumber;
        }

        $payload = $this->httpClient->request('POST', '/orders/' . urlencode($id) . '/status', [
            'json' => $body,
        ]);

        $response = ChangeStatusResponse::fromArray($payload);
        $this->guardStatus($response->getStatus(), $response->getErrors());

        return $response;
    }

    /**
     * @throws EmpikApiException|EmpikHttpException|EmpikValidationException
     */
    public function addInvoice(string $id, string $path, string $type): AddInvoiceResponse
    {
        if ($id === '') {
            throw new EmpikValidationException('Order id cannot be empty');
        }

        if ($type === '') {
            throw new EmpikValidationException('Invoice type cannot be empty');
        }

        if (!is_readable($path)) {
            throw new EmpikValidationException('Invoice file is not readable: ' . $path);
        }

        $filePart = DataPart::fromPath($path);
        $formData = new FormDataPart([
            'type' => $type,
            'file' => $filePart,
        ]);

        $payload = $this->httpClient->request('POST', '/orders/' . urlencode($id) . '/invoice', [
            'body' => $formData->bodyToIterable(),
            'headers' => $formData->getPreparedHeaders()->toArray(),
        ]);

        $response = AddInvoiceResponse::fromArray($payload);
        $this->guardStatus($response->getStatus(), $response->getErrors());

        return $response;
    }

    private function normalizeDate(DateTimeInterface|string $value): DateTimeImmutable
    {
        if ($value instanceof DateTimeInterface) {
            return DateTimeImmutable::createFromInterface($value);
        }

        $date = DateTimeImmutable::createFromFormat(DateTimeInterface::ATOM, (string) $value);

        if ($date === false) {
            try {
                $date = new DateTimeImmutable((string) $value);
            } catch (\Exception $exception) {
                throw new EmpikValidationException('Invalid date value: ' . $value);
            }
        }

        return $date;
    }

    /**
     * @param array<int, ErrorDto> $errors
     * @throws EmpikApiException
     */
    private function guardStatus(string $status, array $errors): void
    {
        if ($status === ResponseStatus::SUCCESS) {
            return;
        }

        if (($status === ResponseStatus::ERROR || $status === ResponseStatus::WARNING) && !empty($errors)) {
            $normalized = array_map(static fn (ErrorDto $error) => [
                'code' => $error->getCode(),
                'message' => $error->getMessage(),
            ], $errors);

            throw new EmpikApiException('Empik API returned status ' . $status, $normalized);
        }
    }
}
