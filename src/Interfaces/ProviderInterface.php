<?php

namespace RaifuCore\Phone\Interfaces;

use RaifuCore\Phone\Requests\Provider\CheckOutgoingCallRequestDto;
use RaifuCore\Phone\Requests\Provider\SendCodeByCallRequestDto;
use RaifuCore\Phone\Requests\Provider\SendOutgoingCallRequestDto;
use RaifuCore\Phone\Requests\Provider\SendSmsRequestDto;
use RaifuCore\Phone\Responses\Provider\CheckOutgoingCallResponseDto;
use RaifuCore\Phone\Responses\Provider\SendCodeByCallResponseDto;
use RaifuCore\Phone\Responses\Provider\SendOutgoingCallResponseDto;
use RaifuCore\Phone\Responses\Provider\SendSmsResponseDto;

interface ProviderInterface
{
    public function __construct(array $config);

    public function getBalance(): float;

    public function sendSMS(SendSmsRequestDto $dto): SendSmsResponseDto;

    public function sendCodeByCall(SendCodeByCallRequestDto $dto): SendCodeByCallResponseDto;

    public function sendOutgoingCall(SendOutgoingCallRequestDto $dto): SendOutgoingCallResponseDto;

    public function checkOutgoingCall(CheckOutgoingCallRequestDto $dto): CheckOutgoingCallResponseDto;
}
