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
        $this->publishes([__DIR__ . '/../config/phone.php' => config_path('phone.php')], 'config');

        $this->_loadLang();
    }

    private function _loadLang(): void
    {
        $langPath = __DIR__ . '/../resources/lang';

        // Load
        $this->loadTranslationsFrom($langPath, 'raifucore');

        // Publish
        $this->publishes([
            $langPath => resource_path('lang/vendor/raifucore'),
        ], 'lang');
    }
}
