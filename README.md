### Install

Require this package with composer using the following command:
```bash
composer require guissilveira/laravel-pgsql-multi-tenant-migrate
```

After updating composer, add the service provider to the `providers` array in `config/app.php`
```php
Guissilveira\Laravel\Commands\MultiTenantMigrateServiceProvider::class
```

Run artisan command:
```bash
php artisan tenant:migrate
```