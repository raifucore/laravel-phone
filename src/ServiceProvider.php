<?php

namespace RaifuCore\Phone;

use Illuminate\Support\ServiceProvider as CoreServiceProvider;

class ServiceProvider extends CoreServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/phone.php', 'phone');
    }

    public function boot(): void
    {
        $this->registerCommands();

        $this->publishes([__DIR__ . '/../config/phone.php' => config_path('phone.php')], 'config');

        $this->_loadLang();
    }

    private function registerCommands(): void
    {
        $this->commands([
            \RaifuCore\Phone\Console\BalanceCommand::class,
            \RaifuCore\Phone\Console\ValidateCommand::class,
        ]);
    }

    private function _loadLang(): void
    {
        $langPath = __DIR__ . '/../resources/lang';

        // Load
        $this->loadTranslationsFrom($langPath, 'raifucore_phone');

        // Publish
        $this->publishes([
            $langPath => resource_path('lang/vendor/raifucore_phone'),
        ], 'lang');
    }
}
