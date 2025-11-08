<?php

namespace App\Gateways\Services;

use App\Gateways\AbstractGateway;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class PaymentsEnterprise extends AbstractGateway
{
    public string $url;
    public string $username;
    public string $password;

    public function __construct()
    {
        $this->url = config('gateways.PaymentsEnterprise.url');
        $this->username = config('gateways.PaymentsEnterprise.username');
        $this->password = config('gateways.PaymentsEnterprise.password');

    }

    public function login()
    {

    }

    public function sendTransaction(array $transaction)
    {
        $response = Http::withHeaders([
            'Gateway-Auth-Token' => $this->username,
            'Gateway-Auth-Secret' => $this->password,
        ])->post($this->url.'/transacoes', [
            'valor' => $transaction['amount'],
            'nome' => $transaction['name'],
            'email' => $transaction['email'],
            'numeroCartao' => $transaction['card_numbers'],
            'cvv' => $transaction['cvv'],
        ]);
        return $response['id'];
    }

    public function listTransactions()
    {

    }

    public function chargeback(string $external_id)
    {
        $response = Http::withHeaders([
            'Gateway-Auth-Token' => $this->username,
            'Gateway-Auth-Secret' => $this->password,
        ])->post($this->url."/transacoes/reembolso", [
            'id' => $external_id
        ]);

        if($response->status() != Response::HTTP_CREATED) {
            return false;
        }
        return true;
    }
}
