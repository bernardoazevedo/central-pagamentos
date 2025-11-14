<?php

namespace App\Gateways;

interface GatewayInterface
{
    public function login();
    public function sendTransaction(array $transaction);
    public function getTransactions();
    public function chargeback(string $external_id);
}
