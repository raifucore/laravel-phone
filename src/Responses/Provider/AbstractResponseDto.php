<?php

namespace RaifuCore\Phone\Responses\Provider;

class AbstractResponseDto
{
    protected bool $status = false;
    protected string|null $provider = null;
    protected string|null $requestId = null;
	protected string|null $error = null;

    public function isSuccess(): bool
    {
        return $this->status;
    }

    public function getProvider(): ?string
    {
        return $this->provider;
    }

    public function getRequestId(): ?string
    {
        return $this->requestId;
    }

    public function getError(): ?string
    {
        return $this->error;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function setProvider(string $provider): self
    {
        $this->provider = $provider;
        return $this;
    }

    public function setRequestId(string $requestId): self
    {
        $this->requestId = $requestId;
        return $this;
    }

    public function setError(string $error): self
    {
        $this->error = $error;
        return $this;
    }

    public function toArray(): array
    {
        return array_filter([
            //'status' => $this->status,
            'provider' => $this->provider,
            'requestId' => $this->requestId,
            'error' => $this->error,
        ]);
    }
}
