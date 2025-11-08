<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Transaction;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::all(['id','name','email']);
        return response()->json($clients, 200);
    }

    public function get($id)
    {
        $client = Client::find($id, ['id', 'name', 'email']);
        $client['transactions'] = Transaction::where('clients_id', $client['id'])
            ->select('id', 'gateways_id', 'external_id', 'status', 'amount', 'card_last_numbers')
            ->orderBy('created_at')
            ->get();
        return response()->json($client, 200);
    }
}
