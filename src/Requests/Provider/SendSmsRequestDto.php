<?php

namespace RaifuCore\Phone\Requests\Provider;

class SendSmsRequestDto
{
    public function __construct(
        protected string $phone,
        protected string $text
    ) {
        $this->phone = preg_replace('[\D]', '', $this->phone);
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getText(): string
    {
        return $this->text;
    }
}
