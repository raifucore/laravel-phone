<?php

namespace RaifuCore\Phone\Providers;

use Illuminate\Support\Str;
use RaifuCore\Phone\Interfaces\ProviderInterface;
use RaifuCore\Phone\Requests\Provider\CheckOutgoingCallRequestDto;
use RaifuCore\Phone\Requests\Provider\SendCodeByCallRequestDto;
use RaifuCore\Phone\Requests\Provider\SendOutgoingCallRequestDto;
use RaifuCore\Phone\Requests\Provider\SendSmsRequestDto;
use RaifuCore\Phone\Responses\Provider\CheckOutgoingCallResponseDto;
use RaifuCore\Phone\Responses\Provider\SendCodeByCallResponseDto;
use RaifuCore\Phone\Responses\Provider\SendOutgoingCallResponseDto;
use RaifuCore\Phone\Responses\Provider\SendSmsResponseDto;

class TestProvider implements ProviderInterface
{
    private string $provider = 'test';
    private string $code;
    private string $number;

    public function __construct(array $config)
    {
        $this->code = $config['code'] ?? '7777';
        $this->number = $config['number'] ?? '78009997777';
    }

    public function getBalance(): float
    {
        return 1000;
    }

    public function sendSMS(SendSmsRequestDto $dto): SendSmsResponseDto
    {
        $response = new SendSmsResponseDto;

        $response
            ->setCode($this->code)
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
            ->setPhoneNumber($this->number)
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
