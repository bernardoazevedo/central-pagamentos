<?php

namespace App\Gateways;

abstract class AbstractGateway implements GatewayInterface
{
    public string $username;
    public string $password;
    public string $token;

    public function __construct()
    {

    }

    public function login()
    {

    }

    public function sendTransaction(array $transaction)
    {

    }

    public function listTransactions()
    {

    }

    public function chargeback(string $external_id)
    {

    }
}
