<?php

namespace Webkul\CashU\Providers;

use Illuminate\Support\ServiceProvider;

class CashUServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../Http/routes.php');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'cashu');

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'cashu');
        
        $this->publishes([
            __DIR__ . '/../Resources/views/overwrite/success.blade.php' => resource_path('themes/velocity/views/checkout/success.blade.php'),
        ]);
        
        $this->publishes([
            __DIR__ . '/../Resources/views/overwrite/OnepageController.php' => resource_path('../packages/Webkul/Shop/src/Http/Controllers/OnepageController.php'),
        ]);
        
     $this->publishes([
            __DIR__ . '/../Resources/views/overwrite/default/success.blade.php' => resource_path('themes/default/views/checkout/success.blade.php'),
        ]);

    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfig();
    }

    /**
     * Register package config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/paymentmethods.php', 'paymentmethods'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/system.php', 'core'
        );
    }
}
