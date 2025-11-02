<?php

namespace RaifuCore\Phone\Requests\Provider;

class CheckOutgoingCallRequestDto
{
    public function __construct(protected string $requestId) {}

    public function getRequestId(): string
    {
        return $this->requestId;
    }
}
