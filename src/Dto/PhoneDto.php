<?php

namespace RaifuCore\Phone\Dto;

class PhoneDto
{
    protected bool $isValid = false;
    protected string|null $source = null;
    protected string|null $code = null;
    protected string|null $body = null;
    protected string|null $full = null;
    protected string|null $countryIso = null;

    public function isValid(): bool
    {
        return $this->isValid;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function getCode(): ?string
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

    public function setIsValid(bool $isValid): self
    {
        $this->isValid = $isValid;
        return $this;
    }

    public function setSource(?string $source): self
    {
        $this->source = $source;
        return $this;
    }

    public function setCode(?string $code): self
    {
        $this->code = $code;
        $this->_setFull();
        return $this;
    }

    public function setBody(?string $body): self
    {
        $this->body = $body;
        $this->_setFull();
        return $this;
    }

    private function _setFull(): void
    {
        $this->full = $this->code . $this->body;
    }

    public function setCountryIso(?string $countryIso): self
    {
        $this->countryIso = $countryIso;
        return $this;
    }
}
