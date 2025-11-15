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
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::all([
            'id',
            'clients_id',
            'status',
            'amount',
        ]);

        $productsArray = [];
        foreach($transactions as $transactionKey => $eachTransaction) {
            $transactions[$transactionKey]['client'] = Client::find($eachTransaction['clients_id'], ['id', 'name', 'email']);
            unset($transactions[$transactionKey]['clients_id']);

            $transactionProducts = TransactionProduct::where('transactions_id', $eachTransaction['id'])
                ->select(['products_id as id', 'quantity'])
                ->get();

            foreach($transactionProducts as $key => $eachProduct) {
                $product = Product::find($eachProduct['id'], ['id', 'name', 'amount']);
                $product->quantity = $eachProduct['quantity'];
                $productsArray[] = $product;
            }
            $transactions[$transactionKey]['products'] = $productsArray;
        }

        return response()->json($transactions, 200);
    }

    public function get($id)
    {
        $transaction = Transaction::find($id, [
            'id',
            'clients_id',
            'status',
            'amount',
        ]);

        $transaction['client'] = Client::find($transaction['clients_id'], ['id', 'name', 'email']);
        unset($transaction['clients_id']);

        $transactionProducts = TransactionProduct::where('transactions_id', $transaction['id'])
            ->select(['products_id as id', 'quantity'])
            ->get();

        $productsArray = [];
        foreach($transactionProducts as $key => $eachProduct) {
            $product = Product::find($eachProduct['id'], ['id', 'name', 'amount']);
            $product->quantity = $eachProduct['quantity'];
            $productsArray[] = $product;
        }
        $transaction['products'] = $productsArray;

        return response()->json($transaction, 200);
    }

    public function create(Request $request)
    {
        $validated = $request->validate([
            'client.name' => 'required|string|max:255',
            'client.email' => 'required|string|max:255|email',
            'payment_info.card_numbers' => 'required|string|size:16',
            'payment_info.cvv' => 'required|string|size:3',
            'products' => 'required',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer'
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
            $productFound = Product::find($eachProduct['id']);
            $total_amount += $productFound->amount * $eachProduct['quantity'];
        }

        $usedGateway = '';
        $activeGateways = Gateway::where('is_active', '=', 1)->orderBy('priority')->get();
        $external_id = '';
        foreach($activeGateways as $eachGateway) {
            $reflection = new ReflectionClass("App\Gateways\Services\\".$eachGateway->class_name);
            $gateway = $reflection->newInstance();
            try {
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
            } catch(BadRequestHttpException $e) {
                return response()->json(['message' => "Error: {$e->getMessage()}"], Response::HTTP_BAD_REQUEST);

            } catch(\Exception $e) {
                // Going to next payment gateway
            }
        }
        if(!$external_id) {
            return response()->json(['message' => 'Error at payment, try again in a few moments'], Response::HTTP_INTERNAL_SERVER_ERROR);
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
                'products_id' => $eachProduct['id'],
                'quantity' => $eachProduct['quantity']
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

        try {
            $gateway->login();
            $gateway->chargeback($transaction->external_id);
        } catch(\Exception $e) {
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
