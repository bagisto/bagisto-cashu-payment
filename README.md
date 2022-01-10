# Introduction

Bagisto CashU Payment add-on allow customers to pay for others using CashU payment gateway.

## Requirements:

- **Bagisto**: v1.3.2

## Installation with composer:
- Run the following command
```
composer require bagisto/bagisto-cashu-payment
```

```
- Goto VerifyCsrfToken.php file and add following line in protected $except array (FilePath - app->Http->Middleware->VerifyCsrfToken.php)
```
'cashu/callback',
'cashu/cancel'
```
- Cashu Merchent Account's URL

    - Return URL

    ```
    https://yourdomain.com/cashu/callback
    ```

    - Sorry URL

    ```
    https://yourdomain.com/cashu/cancel
    ```

- Run these commands below to complete the setup
```
composer dump-autoload
```
```
php artisan optimize
```

## Installation without composer:

- Unzip the respective extension zip and then merge "packages" folder into project root directory.
- Goto config/app.php file and add following line under 'providers'.

```
Webkul\CashU\Providers\CashUServiceProvider::class
```

- Goto composer.json file and add following line under 'psr-4'

```
"Webkul\\CashU\\": "packages/Webkul/CashU/src"
```

- Goto VerifyCsrfToken.php file and add following line in protected $except array (FilePath - app->Http->Middleware->VerifyCsrfToken.php)

```
'cashu/callback',
'cashu/cancel'
```

- Cashu Merchent Account's URL

    - Return URL

    ```
    https://yourdomain.com/cashu/callback
    ```

    - Sorry URL

    ```
    https://yourdomain.com/cashu/cancel
    ```

- Run these commands below to complete the setup

```
composer dump-autoload
```
```
php artisan optimize
```

> That's it, now just execute the project on your specified domain.
