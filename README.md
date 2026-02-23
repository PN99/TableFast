# TableFast - Restaurant Reservation System

Moderní rezervační systém pro restaurace.

## Jak spustit projekt (Laravel Sail)

Projekt využívá Docker přes nástroj Laravel Sail. Před spuštěním se ujistěte, že máte nainstalovaný Docker Desktop.

### 1. Spuštění kontejnerů
V kořenovém adresáři projektu spusťte:
```bash
./vendor/bin/sail up -d
```
*Poznámka: Pokud jste na Windows a `sail` skript hlásí chybu cest, můžete použít:*
```bash
php vendor/laravel/sail/bin/sail up -d
```

### 2. Přístup k aplikaci
- **Webová aplikace**: [http://localhost:8100](http://localhost:8100)
- **Mailpit (E-maily)**: [http://localhost:8125](http://localhost:8125)
- **Meilisearch**: [http://localhost:7700](http://localhost:7700)

### 3. Databáze
- **Port**: `33060` (mapováno z vnitřního 3306)
- **Uživatel**: `sail`
- **Heslo**: `password`
- **Databáze**: `laravel`

### 4. Spuštění testů
```bash
./vendor/bin/sail pest
```

## Vývojový Stack
- **Backend**: Laravel 11
- **Frontend**: Livewire 3 + Tailwind CSS
- **Databáze**: MySQL (přes Docker)
- **Testování**: Pest PHP

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
