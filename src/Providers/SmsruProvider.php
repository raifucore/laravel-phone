<?php

namespace RaifuCore\Phone\Providers;

use RaifuCore\Phone\Exceptions\ProviderRequestException;
use RaifuCore\Phone\Interfaces\ProviderInterface;
use RaifuCore\Phone\Requests\Provider\CheckOutgoingCallRequestDto;
use RaifuCore\Phone\Requests\Provider\SendCodeByCallRequestDto;
use RaifuCore\Phone\Requests\Provider\SendOutgoingCallRequestDto;
use RaifuCore\Phone\Requests\Provider\SendSmsRequestDto;
use RaifuCore\Phone\Responses\Provider\CheckOutgoingCallResponseDto;
use RaifuCore\Phone\Responses\Provider\SendCodeByCallResponseDto;
use RaifuCore\Phone\Responses\Provider\SendOutgoingCallResponseDto;
use RaifuCore\Phone\Responses\Provider\SendSmsResponseDto;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SmsruProvider implements ProviderInterface
{
    private string $provider = 'smsru';
    private string $apiKey;
    private string $sender;

    public function __construct(array $config)
    {
        $this->apiKey = $config['apikey'] ?? '';
        $this->sender = $config['sender'] ?? '';
    }

    private function _code(): string
    {
        return mt_rand(1111, 9999);
    }

    /**
     * @throws ConnectionException
     * @throws ProviderRequestException
     */
    public function getBalance(): float
    {
        $client = Http::timeout(2)
            ->retry(2, 200)
            ->get('https://sms.ru/my/balance', [
                'api_id' => $this->apiKey,
                'json' => '1'
            ]);

        $aResponse = $client->json();

        if (empty($aResponse['status']) || $aResponse['status'] !== 'OK') {
            throw new ProviderRequestException($aResponse['status_text'] ?? 'Provider error');
        }

        if (!isset($aResponse['balance'])) {
            throw new ProviderRequestException('There is no `balance` in the response');
        }

        return floatval($aResponse['balance']);
    }

    public function sendSMS(SendSmsRequestDto $dto): SendSmsResponseDto
    {
        $response = new SendSmsResponseDto;

        $response
            ->setCode($this->_code())
            ->setProvider($this->provider)
            ->setRequestId(Str::uuid()->toString())
            ->setStatus(true);

        return $response;
    }

    public function sendCodeByCall(SendCodeByCallRequestDto $dto): SendCodeByCallResponseDto
    {
        $response = new SendCodeByCallResponseDto;

        $response
            ->setProvider($this->provider)
            ->setRequestId(Str::uuid()->toString())
            ->setStatus(true);

        return $response;
    }

    public function sendOutgoingCall(SendOutgoingCallRequestDto $dto): SendOutgoingCallResponseDto
    {
        $response = new SendOutgoingCallResponseDto;
        $response
            ->setProvider($this->provider);

        try {
            $client = Http::timeout(3)
                ->get('https://sms.ru/callcheck/add', [
                    'api_id' => $this->apiKey,
                    'phone' => $dto->getPhone(),
                    'json' => '1'
                ]);

            $aResponse = $client->json();

            if (empty($aResponse['status']) || $aResponse['status'] !== 'OK') {
                throw new \Exception($aResponse['status_text'] ?? 'Ошибка провайдера');
            }

            $response
                ->setRequestId($aResponse['check_id'])
                ->setPhoneNumber(preg_replace('[\D]', '', $aResponse['call_phone']))
                ->setStatus(true);

        } catch (\Throwable $e) {
            return $response->setStatus(false)->setError($e->getMessage());
        }

        return $response;
    }

    public function checkOutgoingCall(CheckOutgoingCallRequestDto $dto): CheckOutgoingCallResponseDto
    {
        $response = new CheckOutgoingCallResponseDto;
        $response
            ->setProvider($this->provider)
            ->setRequestId($dto->getRequestId());

        try {
            $client = Http::timeout(3)
                ->get('https://sms.ru/callcheck/status', [
                    'api_id' => $this->apiKey,
                    'check_id' => $dto->getRequestId(),
                    'json' => '1'
                ]);

            $aResponse = $client->json();

            if (empty($aResponse['status']) || $aResponse['status'] !== 'OK') {
                throw new \Exception($aResponse['status_text'] ?? 'Ошибка провайдера');
            }

            $response->setStatus(($aResponse['check_status'] ?? null) == 401);

        } catch (\Throwable $e) {
            return $response->setStatus(false)->setError($e->getMessage());
        }

        return $response;
    }
}
