<?php

namespace App\Http\Controllers;

use App\Enums\TransactionStatus;
use App\Gateways\Services\PagamentosCorp;
use App\Models\Client;
use App\Models\Gateway;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionProduct;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use ReflectionClass;

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

        foreach($transactions as $transactionKey => $eachTransaction) {
            $transactions[$transactionKey]['client'] = Client::find($eachTransaction['clients_id'], ['id', 'name', 'email']);
            unset($transactions[$transactionKey]['clients_id']);

            $transactions[$transactionKey]['gateway'] = Gateway::find($eachTransaction['gateways_id'], ['id', 'name']);
            unset($transactions[$transactionKey]['gateways_id']);

            $transactions[$transactionKey]['products'] = TransactionProduct::where('transactions_id', $transactions[$transactionKey]['id'])
                ->select('products_id as id')
                ->get();
            foreach($transactions[$transactionKey]['products'] as $productKey => $eachProduct) {
                $transactions[$transactionKey]['products'][$productKey] = Product::find($eachProduct['id'], ['id', 'name', 'amount']);
            }
        }

        return response()->json($transactions, 200);
    }

    public function get($id)
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

    public function create(Request $request)
    {
        $validated = $request->validate([
            'client.name' => 'required|string|max:255',
            'client.email' => 'required|string|max:255|email',
            'payment_info.card_numbers' => 'required|string|size:16',
            'payment_info.cvv' => 'required|string|size:3',
            'products.*' => 'exists:products,id'
        ]);

        $client = Client::where('email', '=', $request->client['email'])->first();
        if(empty($client)) {
            $client = Client::create([
                'name' => $request->client['name'],
                'email' => $request->client['email'],
            ]);
        }

        $total_amount = 0;
        foreach($request->products as $eachProduct) {
            $eachProduct = Product::find($eachProduct);
            $total_amount += $eachProduct->amount;
        }

        $usedGateway = '';
        $activeGateways = Gateway::where('is_active', '=', 1)->orderBy('priority')->get();
        foreach($activeGateways as $eachGateway) {
            $reflection = new ReflectionClass("App\Gateways\Services\\".$eachGateway->class_name);
            $gateway = $reflection->newInstance();
            $gateway->login();
            $external_id = $gateway->sendTransaction([
                'name' => $request->client['name'],
                'email' => $request->client['email'],
                'amount' => $total_amount,
                'card_numbers' => $request->payment_info['card_numbers'],
                'cvv' => $request->payment_info['cvv'],
            ]);
            if($external_id) {
                $usedGateway = $eachGateway;
                break;
            }
        }

        $transaction = Transaction::create([
            'clients_id' => $client->id,
            'status' => TransactionStatus::PAID,
            'external_id' => $external_id,
            'gateways_id' => $usedGateway->id,
            'card_last_numbers' => substr($request->payment_info['card_numbers'], -4),
            'amount' => $total_amount,
        ]);

        foreach($request->products as $eachProduct) {
            TransactionProduct::create([
                'transactions_id' => $transaction->id,
                'products_id' => $eachProduct,
            ]);
        }

        return response()->json([
            'id' => $transaction->id,
            'amount' => $transaction->amount,
            'status' => $transaction->status,
        ], Response::HTTP_CREATED);
    }

    public function chargeback(string $id)
    {
        $transaction = Transaction::find($id);
        if(!$transaction) {
            return response()->json(['message' => 'ID not found'], Response::HTTP_NOT_FOUND);
        }

        $gateway = Gateway::find($transaction->gateways_id);

        $reflection = new ReflectionClass("App\Gateways\Services\\".$gateway->class_name);
        $gateway = $reflection->newInstance();

        $gateway->login();
        if(!$gateway->chargeback($transaction->external_id)) {
            return response()->json(['message' => 'Error at chargeback, try again in a few moments'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $transaction->status = TransactionStatus::CHARGED_BACK;
        $transaction->save();

        return response()->json([
            'id' => $transaction->id,
            'amount' => $transaction->amount,
            'status' => $transaction->status,
        ], Response::HTTP_CREATED);
    }
}
