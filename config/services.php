<?php

declare(strict_types=1);

use Empik\Marketplace\Client\EmpikHttpClient;
use Empik\Marketplace\Client\EmpikHttpClientInterface;
use Empik\Marketplace\Config\EmpikConfig;
use Empik\Marketplace\Service\OrdersService;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Contracts\HttpClient\HttpClientInterface;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services->set(EmpikConfig::class)
        ->args([
            '%env(EMPIK_API_BASE_URL)%',
            '%env(EMPIK_API_KEY)%',
            10.0,
            null,
        ]);

    $services->set(EmpikHttpClient::class)
        ->args([
            service(HttpClientInterface::class),
            service(EmpikConfig::class),
        ]);

    $services->alias(EmpikHttpClientInterface::class, EmpikHttpClient::class);

    $services->set(OrdersService::class)
        ->args([
            service(EmpikHttpClientInterface::class),
        ]);
};
