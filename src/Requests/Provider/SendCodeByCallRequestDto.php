<?php

namespace RaifuCore\Phone\Requests\Provider;

class SendCodeByCallRequestDto
{
    public function __construct(protected string $phone)
    {
        $this->phone = preg_replace('[\D]', '', $this->phone);
    }

    public function getPhone(): string
    {
        return $this->phone;
    }
}
