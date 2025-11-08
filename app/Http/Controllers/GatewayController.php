<?php

namespace App\Http\Controllers;

use App\Models\Gateway;
use Illuminate\Http\Request;

class GatewayController extends Controller
{
    public function edit(Request $request, int $id)
    {
        $request->validate([
            'is_active' => 'boolean',
            'priority' => 'integer'
        ]);

        $gateway = Gateway::find($id);

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
        ], 200);
    }
}
