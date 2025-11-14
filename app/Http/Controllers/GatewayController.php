<?php

namespace App\Http\Controllers;

use App\Models\Gateway;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use ReflectionClass;

class GatewayController extends Controller
{
    public function update(Request $request, int $id)
    {
        $request->validate([
            'is_active' => 'boolean',
            'priority' => 'integer'
        ]);

        $gateway = Gateway::find($id);
        if(empty($gateway)) {
            return response()->json(['message' => 'ID not found'], Response::HTTP_NOT_FOUND);
        }

        if(isset($request->is_active)) {
            $gateway->is_active = $request->is_active;
        }
        if(isset($request->priority)) {
            $gateway->priority = $request->priority;
        }

        $gateway->save();

        return response()->json([
            "id" => $gateway->id,
            "name" => $gateway->name,
            "is_active" => boolval($gateway->is_active),
            "priority" => $gateway->priority,
        ], Response::HTTP_OK);
    }

    public function index(Request $request, int $id)
    {
        $gateway = Gateway::find($id);
        if(empty($gateway)) {
            return response()->json(['message' => 'ID not found'], Response::HTTP_NOT_FOUND);
        }

        $reflection = new ReflectionClass("App\Gateways\Services\\".$gateway->class_name);
        $gateway = $reflection->newInstance();

        try {
            $gateway->login();
            $transactions = $gateway->getTransactions();
        } catch(\Exception $e) {
            return response()->json(['message' => 'Error listing transactions'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json($transactions, Response::HTTP_OK);
    }
}
