<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Gateway;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionProduct;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::all([
            'id',
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

    public function getTransaction($id)
    {
        $transaction = Transaction::find($id, [
            'id',
            'clients_id',
            'gateways_id',
            'external_id',
            'status',
            'amount',
            'card_last_numbers'
        ]);

        $transaction['client'] = Client::find($transaction['clients_id'], ['id', 'name', 'email']);
        unset($transaction['clients_id']);

        $transaction['gateway'] = Gateway::find($transaction['gateways_id'], ['id', 'name']);
        unset($transaction['gateways_id']);

        $transaction['products'] = TransactionProduct::where('transactions_id', $transaction['id'])
            ->select('products_id as id')
            ->get();
        foreach($transaction['products'] as $key => $eachProduct) {
            $transaction['products'][$key] = Product::find($eachProduct['id'], ['id', 'name', 'amount']);
        }

        return response()->json($transaction, 200);
    }
}
