<?php

namespace RaifuCore\Phone\Providers;

use RaifuCore\Phone\Enums\ProviderLabelEnum;
use RaifuCore\Phone\Exceptions\ProviderParamsException;
use RaifuCore\Phone\Interfaces\ProviderInterface;

class Factory
{
    private ProviderLabelEnum $label;
    private static array $providers = [];

    public function __construct(ProviderLabelEnum|null $label = null)
    {
        $this->label = $label
            ?? ProviderLabelEnum::tryFrom(config('phone.provider'))
            ?? ProviderLabelEnum::TEST;
    }

    /**
     * @throws ProviderParamsException
     */
    public function init(): ProviderInterface
    {
        if (empty(self::$providers[$this->label->value])) {

            $params = config('phone');

            if (empty($params['providers'][$this->label->value])) {
                throw new ProviderParamsException("Parameters for provider {$this->label->value} are empty");
            }
            $providerParams = $params['providers'][$this->label->value];

            $providerClass = $providerParams['class'] ?? '';
            if (!class_exists($providerClass)) {
                throw new ProviderParamsException("Class of provider {$this->label->value} does not exist");
            }

            $providerConfig = $providerParams['config'] ?? null;
            if (!is_array($providerConfig)) {
                throw new ProviderParamsException("Config of provider {$this->label->value} is empty");
            }

            try {
                self::$providers[$this->label->value] = app()->make($providerClass, ['config' => $providerConfig,]);
            } catch (\Throwable) {
                throw new ProviderParamsException("Can\'t make provider instance");
            }
        }

        return self::$providers[$this->label->value];
    }
}
