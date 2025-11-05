<?php

namespace App\Http\Controllers;

use App\Models\Client;

class ClientController extends Controller
{
    public function index()
    {
        $products = Client::all(['id','name','email']);
        return response()->json($products, 200);
    }
}
