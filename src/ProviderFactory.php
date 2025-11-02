<?php

namespace App\Modules\Phone;

use RaifuCore\Phone\Exceptions\ProviderParamsException;
use RaifuCore\Phone\Interfaces\ProviderInterface;
use RaifuCore\Phone\Providers\TestProvider;
use RaifuCore\Phone\Requests\Provider\CheckOutgoingCallRequestDto;
use RaifuCore\Phone\Requests\Provider\SendCodeByCallRequestDto;
use RaifuCore\Phone\Requests\Provider\SendOutgoingCallRequestDto;
use RaifuCore\Phone\Requests\Provider\SendSmsRequestDto;
use RaifuCore\Phone\Responses\Provider\CheckOutgoingCallResponseDto;
use RaifuCore\Phone\Responses\Provider\SendCodeByCallResponseDto;
use RaifuCore\Phone\Responses\Provider\SendOutgoingCallResponseDto;
use RaifuCore\Phone\Responses\Provider\SendSmsResponseDto;

class ProviderFactory
{
    private static ProviderInterface|null $provider = null;

    /**
     * @throws ProviderParamsException
     */
    public static function init(): void
    {
        if (self::$provider) {
            return;
        }

        $params = config('phone');
        if (empty($params)) {
            throw new ProviderParamsException('SMS parameters are not set');
        }
        if (empty($params['provider']) || empty($params['providers'])) {
            throw new ProviderParamsException('Provider or their params are not set');
        }

        $provider = strtolower($params['provider']);
        $providers = $params['providers'];
        if (empty($providers[$provider])) {
            throw new ProviderParamsException("Parameters for provider {$provider} are empty");
        }
        $providerParams = $providers[$provider];

        $providerClass = $providerParams['class'] ?? '';
        if (!class_exists($providerClass)) {
            throw new ProviderParamsException("Class of provider {$provider} does not exist");
        }

        $providerConfig = $providerParams['config'] ?? null;
        if (!is_array($providerConfig)) {
            throw new ProviderParamsException("Config of provider {$provider} is empty");
        }

        try {
            self::$provider = app()->make($providerClass, [
                'config' => $providerConfig,
                'isTest' => !empty($params['isTest'])
            ]);
        } catch (\Throwable) {
            throw new ProviderParamsException("Cant make instance");
        }
    }

    /**
     * @throws ProviderParamsException
     */
    public static function isTest(): bool
    {
        self::init();
        return self::$provider instanceof TestProvider;
    }

    /**
     * @throws ProviderParamsException
     */
    public static function getBalance(): float
    {
        self::init();
        return self::$provider->getBalance();
    }

    /**
     * @throws ProviderParamsException
     */
    public static function sendSms(SendSmsRequestDto $dto): SendSmsResponseDto
    {
        self::init();
        return self::$provider->sendSMS($dto);
    }

    /**
     * @throws ProviderParamsException
     */
    public static function sendCodeByCall(SendCodeByCallRequestDto $dto): SendCodeByCallResponseDto
    {
        self::init();
        return self::$provider->sendCodeByCall($dto);
    }

    /**
     * @throws ProviderParamsException
     */
    public static function sendOutgoingCall(SendOutgoingCallRequestDto $dto): SendOutgoingCallResponseDto
    {
        self::init();
        return self::$provider->sendOutgoingCall($dto);
    }

    /**
     * @throws ProviderParamsException
     */
    public static function checkOutgoingCall(CheckOutgoingCallRequestDto $dto): CheckOutgoingCallResponseDto
    {
        self::init();
        return self::$provider->checkOutgoingCall($dto);
    }
}
