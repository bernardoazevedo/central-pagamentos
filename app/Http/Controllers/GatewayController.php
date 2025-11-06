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

        $gateway->is_active = $request->is_active;
        $gateway->priority = $request->priority;

        $gateway->save();

        return response()->json($gateway, 200);
    }
}
