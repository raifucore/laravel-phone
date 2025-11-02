<?php

namespace RaifuCore\Phone\Providers;

use RaifuCore\Phone\Interfaces\ProviderInterface;
use RaifuCore\Phone\Requests\Provider\CheckOutgoingCallRequestDto;
use RaifuCore\Phone\Requests\Provider\SendCodeByCallRequestDto;
use RaifuCore\Phone\Requests\Provider\SendOutgoingCallRequestDto;
use RaifuCore\Phone\Requests\Provider\SendSmsRequestDto;
use RaifuCore\Phone\Responses\Provider\CheckOutgoingCallResponseDto;
use RaifuCore\Phone\Responses\Provider\SendCodeByCallResponseDto;
use RaifuCore\Phone\Responses\Provider\SendOutgoingCallResponseDto;
use RaifuCore\Phone\Responses\Provider\SendSmsResponseDto;
use Illuminate\Support\Str;

class TestProvider implements ProviderInterface
{
    private string $provider = 'test';

    public function __construct(array $config) {}

    private function _code(): string
    {
        return config('phone.test_data.code') ?? '7777';
    }

    public function getBalance(): float
    {
        return 1000;
    }

    public function sendSMS(SendSmsRequestDto $dto): SendSmsResponseDto
    {
        $response = new SendSmsResponseDto;

        $response
            ->setCode($this->_code())
            ->setProvider($this->provider)
            ->setRequestId(Str::uuid()->toString())
            ->setStatus(true);

        return $response;
    }

    public function sendCodeByCall(SendCodeByCallRequestDto $dto): SendCodeByCallResponseDto
    {
        $response = new SendCodeByCallResponseDto;

        $response
            ->setProvider($this->provider)
            ->setRequestId(Str::uuid()->toString())
            ->setStatus(true);

        return $response;
    }

    public function sendOutgoingCall(SendOutgoingCallRequestDto $dto): SendOutgoingCallResponseDto
    {
        $response = new SendOutgoingCallResponseDto;

        $response
            ->setProvider($this->provider)
            ->setRequestId(Str::uuid()->toString())
            ->setPhoneNumber(config('phone.test_data.number'))
            ->setStatus(true);

        return $response;
    }

    public function checkOutgoingCall(CheckOutgoingCallRequestDto $dto): CheckOutgoingCallResponseDto
    {
        $response = new CheckOutgoingCallResponseDto;

        $response
            ->setProvider($this->provider)
            ->setRequestId($dto->getRequestId())
            ->setStatus(true);

        return $response;
    }
}
