<?php

namespace RaifuCore\Phone\Dto;

class TemplateDto
{
    protected ?int $code = null;
    protected ?int $length = null;
    protected ?int $length_max = null;
    protected ?string $regex = null;
    protected ?string $mask = null;

    public function __construct(protected string $countryIso) {}

    public function getCountryIso(): string
    {
        return $this->countryIso;
    }

    public function getCode(): ?int
    {
        return $this->code;
    }

    public function getLength(): ?int
    {
        return $this->length;
    }

    public function getLengthMax(): ?int
    {
        return $this->length_max;
    }

    public function getRegex(): ?string
    {
        return $this->regex;
    }

    public function getMask(): ?string
    {
        return $this->mask;
    }

    public function fromArray(array $a): self
    {
        $this->code = $a['code'] ?? null;
        $this->length = $a['length'] ?? null;
        $this->length_max = $a['length_max'] ?? null;
        $this->regex = $a['regex'] ?? null;
        $this->mask = $a['mask'] ?? null;

        return $this;
    }
}
