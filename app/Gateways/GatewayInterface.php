<?php

namespace App\Gateways;

interface GatewayInterface
{
    public function login();
    public function sendTransaction(array $transaction);
    public function listTransactions();
    public function chargeback(string $external_id);
}
