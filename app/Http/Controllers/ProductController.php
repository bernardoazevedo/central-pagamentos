<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all(['id','name','amount']);
        if(count($products) == 0) {
            return response()->json(['message' => 'No items stored'], Response::HTTP_NOT_FOUND);
        }
        
        return response()->json($products, 200);
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'amount' => 'required|integer',
        ]);

        $product = Product::create($request->toArray());
        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'amount' => $product->amount,
        ], Response::HTTP_CREATED);
    }

    public function get($id)
    {
        $product = Product::find($id, ['id', 'name', 'amount']);
        if(empty($product)) {
            return response()->json(['message' => 'ID not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($product, Response::HTTP_OK);
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'name' => 'max:255',
            'amount' => 'integer',
        ]);

        $product = Product::find($id);
        if(empty($product)) {
            return response()->json(['message' => 'ID not found'], Response::HTTP_NOT_FOUND);
        }

        if(isset($request->name)) {
            $product->name = $request->name;
        }
        if(isset($request->amount)) {
            $product->amount = $request->amount;
        }
        $product->save();

        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'amount' => $product->amount,
        ], Response::HTTP_OK);
    }

    public function delete($id)
    {
        $product = Product::find($id);
        if(empty($product)) {
            return response()->json(['message' => 'ID not found'], Response::HTTP_NOT_FOUND);
        }

        $product->delete();
        return response()->json(['message' => "$id deleted"], Response::HTTP_OK);
    }
}
