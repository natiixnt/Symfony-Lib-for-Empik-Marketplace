# Empik Marketplace PHP SDK

Biblioteka do integracji z Empik Marketplace (obsługa zamówień) gotowa do wpięcia w projekt Symfony. Zapewnia zgodne ze specyfikacją metody `getOrders`, `getOrder`, `changeStatus`, `addInvoice`, mapowanie na DTO oraz obsługę błędów.

## Wymagania
- PHP >= 8.1
- symfony/http-client (domyślny klient HTTP)
- Opcjonalnie Symfony DI do wstrzykiwania serwisów

## Instalacja
```bash
composer require empik/marketplace-sdk
```

## Konfiguracja
Ustaw dane dostępu (np. w `.env`):
```env
EMPIK_API_BASE_URL="https://api.empik.com/marketplace"
EMPIK_API_KEY="your-api-token"
```

Jeśli korzystasz z Symfony, możesz zaimportować dostarczony plik konfiguracyjny:
```php
// config/services.php (w aplikacji)
$container->import('../vendor/empik/marketplace-sdk/config/services.php');
```

o albo zdefiniować serwisy ręcznie:
```php
use Empik\Marketplace\Client\EmpikHttpClient;
use Empik\Marketplace\Config\EmpikConfig;
use Empik\Marketplace\Service\OrdersService;
use Symfony\Component\HttpClient\HttpClient;

$config = new EmpikConfig($_ENV['EMPIK_API_BASE_URL'], $_ENV['EMPIK_API_KEY']);
$httpClient = new EmpikHttpClient(HttpClient::create(), $config);
$orders = new OrdersService($httpClient);
```

## Użycie
### Pobranie zamówień z zakresu dat
```php
$response = $orders->getOrders('2023-01-01', '2023-01-31');

foreach ($response->getData() as $order) {
    echo $order->getId().' '.$order->getStatus();
}
```

### Pobranie konkretnego zamówienia
```php
$response = $orders->getOrder('ORDER_ID');
$order = $response->getData();
```

### Zmiana statusu (np. wysyłka ze śledzeniem)
```php
$response = $orders->changeStatus('ORDER_ID', 'SENT', 'TRACKING_NUMBER');
```

### Dodanie faktury do zamówienia
```php
$response = $orders->addInvoice('ORDER_ID', '/path/to/invoice.pdf', 'faktura vat');
$invoiceId = $response->getData()->getId();
```

## Obsługa błędów
- `EmpikValidationException` - błędy walidacji parametrów (np. brak tracking_number przy statusie SENT)
- `EmpikApiException` - status `error` lub `warning` z API (z kodami błędów)
- `EmpikHttpException` - problemy HTTP/transport lub nieprawidłowy JSON

## Testy
```bash
composer install
vendor/bin/phpunit
```

## Struktura katalogów
- `src/Config` - konfiguracja (EmpikConfig)
- `src/Client` - niski poziom HTTP (EmpikHttpClient, interfejs)
- `src/Service` - API wysokiego poziomu (OrdersService)
- `src/Dto` - DTO dla zamówień, produktów, płatności, faktur, błędów
- `src/Response` - obiekty odpowiedzi i statusy
- `src/Exception` - wyjątki domenowe
- `config/services.php` - przykładowa konfiguracja serwisów Symfony
- `tests` - testy jednostkowe i integracyjne (mock HTTP)
