<?php

namespace App\Http\Controllers;

use App\Models\ReorderRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class ReorderRequestController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($request->user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $reorder = ReorderRequest::create([
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'status' => 'pending',
        ]);

        return response()->json($reorder, 201);
    }

    public function update(Request $request, ReorderRequest $reorderRequest)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        if ($request->user()->role !== 'warehouse_manager') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $reorderRequest->update(['status' => $request->status]);

        if ($request->status === 'approved') {
            $product = Product::find($reorderRequest->product_id);
            $product->increment('quantity', $reorderRequest->quantity);
        }

        return $reorderRequest;
    }
}
