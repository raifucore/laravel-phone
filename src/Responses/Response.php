<?php

namespace RaifuCore\Phone\Responses;

class Response
{
    protected bool $status = false;
    protected bool $isTest = false;
    protected ?string $provider = null;
    protected ?string $method = null;
    protected ?string $code = null;
    protected ?string $phone = null;
    protected ?string $number = null;
    protected ?float $cost = null;
    protected ?float $balance = null;
    protected ?string $requestId = null;
    protected ?string $message = null;
	protected ?string $error = null;
    protected ?string $log = null;
    protected ?int $logId = null;

    public function isStatus(): bool
    {
        return $this->status;
    }

    public function isTest(): bool
    {
        return $this->isTest;
    }

    public function getProvider(): ?string
    {
        return $this->provider;
    }

    public function getMethod(): ?string
    {
        return $this->method;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function getCost(): ?float
    {
        return $this->cost;
    }

    public function getBalance(): ?float
    {
        return $this->balance;
    }

    public function getRequestId(): ?string
    {
        return $this->requestId;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function getError(): ?string
    {
        return $this->error;
    }

    public function getLog(): ?string
    {
        return $this->log;
    }

    public function getLogId(): ?int
    {
        return $this->logId;
    }

    public function setStatus(bool $status): Response
    {
        $this->status = $status;
        return $this;
    }

    public function setIsTest(bool $isTest): Response
    {
        $this->isTest = $isTest;
        return $this;
    }

    public function setProvider(string $provider): Response
    {
        $this->provider = $provider;
        return $this;
    }

    public function setMethod(string $method): Response
    {
        $this->method = $method;
        return $this;
    }

    public function setCode(string $code): Response
    {
        $this->code = $code;
        return $this;
    }

    public function setPhone(string $phone): Response
    {
        $this->phone = $phone;
        return $this;
    }

    public function setNumber(string $number): Response
    {
        $this->number = $number;
        return $this;
    }

    public function setCost(float $cost): Response
    {
        $this->cost = $cost;
        return $this;
    }

    public function setBalance(float $balance): Response
    {
        $this->balance = $balance;
        return $this;
    }

    public function setRequestId(string $requestId): Response
    {
        $this->requestId = $requestId;
        return $this;
    }

    public function setMessage(string $message): Response
    {
        $this->message = $message;
        return $this;
    }

    public function setError(string $error): Response
    {
        $this->error = $error;
        return $this;
    }

    public function setLog(string $log): Response
    {
        $this->log = $log;
        return $this;
    }

    public function setLogId(int $logId): Response
    {
        $this->logId = $logId;
        return $this;
    }

    public function toLog(): string
    {
        return json_encode([
            'status' => $this->isStatus(),
            'isTest' => $this->isTest(),
            'provider' => $this->getProvider(),
            'method' => $this->getMethod(),
            'code' => $this->getCode(),
            'phone' => $this->getPhone(),
            'number' => $this->getNumber(),
            'cost' => $this->getCost(),
            'balance' => $this->getBalance(),
            'request_id' => $this->getRequestId(),
            'message' => $this->getMessage(),
            'error' => $this->getError(),
            'log' => $this->getLog(),
        ], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
    }
}
