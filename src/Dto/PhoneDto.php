<?php

namespace RaifuCore\Phone\Dto;

class PhoneDto
{
    public function __construct(
        protected int    $code,
        protected string $body,
        protected string $full,
        protected string $countryIso,
    ) {}

    public function getCode(): ?int
    {
        return $this->code;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function getFull(): ?string
    {
        return $this->full;
    }

    public function getCountryIso(): ?string
    {
        return $this->countryIso;
    }
}
