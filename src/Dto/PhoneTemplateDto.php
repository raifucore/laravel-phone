<?php

namespace RaifuCore\Phone\Dto;

class PhoneTemplateDto
{
    protected string|null $code = null;
    protected string|null $mask = null;
    protected string|null $bodyMask = null;
    protected string|null $placeholder = null;
    protected string|null $regex = null;
    protected string|null $countryIso = null;

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function getMask(): ?string
    {
        return $this->mask;
    }

    public function getBodyMask(): ?string
    {
        return $this->bodyMask;
    }

    public function getPlaceholder(): ?string
    {
        return $this->placeholder;
    }

    public function getRegex(): ?string
    {
        return $this->regex;
    }

    public function getCountryIso(): ?string
    {
        return $this->countryIso;
    }

    public function fromArray(array $a): self
    {
        $this->code = $a['code'] ?? null;
        $this->mask = $a['mask'] ?? null;
        $this->bodyMask = $a['body_mask'] ?? null;
        $this->placeholder = $a['placeholder'] ?? null;
        $this->regex = $a['regex'] ?? null;
        $this->countryIso = $a['country_iso'] ?? null;

        return $this;
    }
}
