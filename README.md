# Introduction

Bagisto CashU Payment add-on allow customers to pay for others using CashU payment gateway.

## Requirements:

- **Bagisto**: v1.3.2

## Installation :
- Run the following command
```
composer require bagisto/bagisto-cashu-payment
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
