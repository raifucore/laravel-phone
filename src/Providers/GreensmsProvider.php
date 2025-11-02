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
use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class GreensmsProvider implements ProviderInterface
{
    private string $provider = 'greensms';
    private string|null $login;
    private string|null $password;

    public function __construct(array $config)
    {
        $this->login = $config['login'] ?? null;
        $this->password = $config['password'] ?? null;
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
            ->get('https://api3.greensms.ru/account/balance', [
                'user' => $this->login,
                'pass' => $this->password,
            ]);

        $array = $client->json();
        if (!isset($array['balance'])) {
            throw new ProviderRequestException('В ответе отсутствуют balance');
        }

        return floatval($array['balance']);
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

        try {
            if ($this->token) {
                $http = Http::timeout(2)
                    ->withHeaders(['Authorization', 'Bearer ' . $this->token])
                    ->retry(2, 200)
                    ->post('https://api3.greensms.ru/call/send', [
                        'to' => $dto->getPhone()
                    ]);
            } else {
                $client = Http::timeout(2)
                    ->retry(2, 200)
                    ->post('https://api3.greensms.ru/call/send', [
                        'to' => $dto->getPhone(),
                        'user' => $this->login,
                        'pass' => $this->password,
                    ]);
            }

            echo "<pre>", print_r($http->json(), true), "</pre>"; die();


            if ($http->successful()) {
                $array = $http->json();
                if (empty($array['code']) || empty($array['request_id'])) {
                    throw new Exception('В ответе отсутствуют code и request_id');
                }
                $this->response
                    ->setPhone($phone)
                    ->setCode($array['code'])
                    ->setRequestId($array['request_id'])
                    ->setStatus(true);
            }
        } catch (\Throwable $e) {

            $error = 'Не удалось сделать звонок';
            if ($e instanceof RequestException) {
                $body = $e->response->json();
                $error = $body['error'] ?? 'Не удалось сделать звонок';
            }

            $this->response
                ->setPhone($phone)
                ->setError($error)
                ->setLog($e->getMessage())
                ->setStatus(false);
        }

        return $this->storeResponse();
    }

    public function sendOutgoingCall(SendOutgoingCallRequestDto $dto): SendOutgoingCallResponseDto
    {
        $response = new SendOutgoingCallResponseDto;
        $response->setProvider($this->provider);

        try {

            $client = Http::timeout(2)
                ->retry(2, 200)
                ->post('https://api3.greensms.ru/call/receive', [
                    'to' => $dto->getPhone(),
                    'user' => $this->login,
                    'pass' => $this->password,
                ]);

            if ($client->successful()) {

                $array = $client->json();
                if (empty($array['number']) || empty($array['request_id'])) {
                    throw new Exception('В ответе отсутствуют number и request_id');
                }

                $response
                    ->setRequestId($array['request_id'])
                    ->setPhoneNumber(preg_replace('[\D]', '', $array['number']))
                    ->setStatus(true);

            }
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
                ->get('https://api3.greensms.ru/call/status', [
                    'id' => $dto->getRequestId(),
                    'user' => $this->login,
                    'pass' => $this->password,
                ]);

            if ($client->successful()) {

                $array = $client->json();

                if (empty($array['status_code']) || empty($array['status'])) {
                    throw new Exception('В ответе отсутствуют status или status_code');
                }

                /**
                 * @see https://api3.greensms.ru/#api-Call-CallStatus
                 * "status_code":
                 *   0 "Status not ready"
                 *   1 "Call success"
                 *   2 "Call failure"
                 *   4 "Call buffered"
                 *   8 "Accepted for delivery"
                 *  16 "Call rejected"
                 *  32 "Status request expired"
                 */

                $response
                    ->setStatus(intval($array['status_code']) === 1);
            }

        } catch (\Throwable $e) {
            return $response->setStatus(false)->setError($e->getMessage());
        }

        return $response;
    }
}
