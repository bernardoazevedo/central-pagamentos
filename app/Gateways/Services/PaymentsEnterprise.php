<?php

namespace App\Gateways\Services;

use App\Gateways\AbstractGateway;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class PaymentsEnterprise extends AbstractGateway
{
    public string $url = 'http://central-pagamentos-gateways:3002';
    public string $token = 'tk_f2198cc671b5289fa856';
    public string $secret = '3d15e8ed6131446ea7e3456728b1211f';

    public function __construct()
    {

    }

    public function login()
    {

    }

    public function sendTransaction(array $transaction)
    {
        $response = Http::withHeaders([
            'Gateway-Auth-Token' => $this->token,
            'Gateway-Auth-Secret' => $this->secret,
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
            'Gateway-Auth-Token' => $this->token,
            'Gateway-Auth-Secret' => $this->secret,
        ])->post($this->url."/transacoes/reembolso", [
            'id' => $external_id
        ]);

        if($response->status() != Response::HTTP_CREATED) {
            return false;
        }
        return true;
    }
}
