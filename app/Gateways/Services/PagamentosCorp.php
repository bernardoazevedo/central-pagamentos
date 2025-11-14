<?php

namespace App\Gateways\Services;

use App\Gateways\GatewayInterface;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class PagamentosCorp implements GatewayInterface
{
    public string $url;
    public string $username;
    public string $password;
    public string $token;

    public function __construct()
    {
        $this->url = config('gateways.PagamentosCorp.url');
        $this->username = config('gateways.PagamentosCorp.username');
        $this->password = config('gateways.PagamentosCorp.password');
    }

    public function login()
    {
        $response = Http::post($this->url.'/login', [
            'email' => $this->username,
            'token' => $this->password,
        ]);
        $this->token = $response['token'];
    }

    public function sendTransaction(array $transaction)
    {
        $response = Http::withToken($this->token)->post($this->url.'/transactions', [
            'amount' => $transaction['amount'],
            'name' => $transaction['name'],
            'email' => $transaction['email'],
            'cardNumber' => $transaction['card_numbers'],
            'cvv' => $transaction['cvv'],
        ]);

        if($response->status() == Response::HTTP_BAD_REQUEST) {
            throw new BadRequestHttpException("The card information provided is invalid");
        }
        return $response['id'];
    }

    public function listTransactions()
    {

    }

    public function chargeback(string $external_id): bool
    {
        $response = Http::withToken($this->token)->post($this->url."/transactions/$external_id/charge_back", []);
        if($response->status() != Response::HTTP_CREATED) {
            throw new Exception("Error at chargeback response");
        }
        return true;
    }
}
