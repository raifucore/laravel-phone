<?php

namespace RaifuCore\Phone\Responses\Provider;

class CheckOutgoingCallResponseDto extends AbstractResponseDto
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
}
