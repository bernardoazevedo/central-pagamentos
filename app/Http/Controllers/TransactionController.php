<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Gateway;
use App\Models\Transaction;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::all([
            'clients_id',
            'gateways_id',
            'external_id',
            'status',
            'amount',
            'card_last_numbers',
        ]);

        foreach($transactions as $key => $eachTransaction) {
            $transactions[$key]['client'] = Client::find($eachTransaction['clients_id'], ['id', 'name', 'email']);
            unset($transactions[$key]['clients_id']);

            $transactions[$key]['gateway'] = Gateway::find($eachTransaction['gateways_id'], ['id', 'name']);
            unset($transactions[$key]['gateways_id']);
        }

        return response()->json($transactions, 200);
    }
}
