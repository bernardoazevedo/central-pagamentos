<?php

namespace App\Gateways\Services;

use App\Gateways\AbstractGateway;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class PagamentosCorp extends AbstractGateway
{
    public string $url = 'http://central-pagamentos-gateways:3001';
    public string $username = 'dev@betalent.tech';
    public string $password = 'FEC9BB078BF338F464F96B48089EB498';
    public string $token;

    public function __construct()
    {

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
        return $response['id'];
    }

    public function listTransactions()
    {

    }

    public function chargeback(string $external_id): bool
    {
        $response = Http::withToken($this->token)->post($this->url."/transactions/$external_id/charge_back", []);
        if($response->status() != Response::HTTP_CREATED) {
            return false;
        }
        return true;
    }
}
