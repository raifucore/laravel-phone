<?php

namespace RaifuCore\Phone\Responses\Provider;

class SendOutgoingCallResponseDto extends AbstractResponseDto
{
    protected string|null $phoneNumber = null;

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;
        return $this;
    }

    public function toArray(): array
    {
        return array_filter(array_merge(parent::toArray(), [
            'phoneNumber' => $this->phoneNumber
        ]));
    }
}
