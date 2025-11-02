<?php

namespace RaifuCore\Phone\Responses\Provider;

class SendSmsResponseDto extends AbstractResponseDto
{
    protected string|null $code = null;

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;
        return $this;
    }

    public function toArray(): array
    {
        return array_filter(array_merge(parent::toArray(), [
            'code' => $this->code
        ]));
    }
}
