<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $carts = $request->user()->carts()->with('product')->get();

        if ($carts->isEmpty()) {
            return response()->json(['error' => 'Cart is empty'], 400);
        }

        $totalAmount = 0;
        $orderItems = [];

        foreach ($carts as $cart) {
            if ($cart->product->quantity < $cart->quantity) {
                return response()->json(['error' => "Insufficient stock for {$cart->product->name}"], 400);
            }
            $totalAmount += $cart->product->price * $cart->quantity;
            $orderItems[] = [
                'product_id' => $cart->product_id,
                'quantity' => $cart->quantity,
                'price' => $cart->product->price,
            ];
        }

        $order = Order::create([
            'user_id' => $request->user()->id,
            'ticket_number' => Str::random(10),
            'total_amount' => $totalAmount,
            'status' => 'pending',
        ]);

        foreach ($orderItems as $item) {
            $order->items()->create($item);
            $product = Product::find($item['product_id']);
            $product->decrement('quantity', $item['quantity']);
        }

        $request->user()->carts()->delete();

        return response()->json($order, 201);
    }

    public function index(Request $request)
    {
        $query = Order::where('user_id', $request->user()->id)->with('items.product');

        if ($request->has('date')) {
            $query->whereDate('created_at', $request->date);
        }

        if ($request->has('ticket_number')) {
            $query->where('ticket_number', $request->ticket_number);
        }

        return $query->get();
    }

    public function show(Order $order)
    {
        return $order->load('items.product');
    }

    public function approve(Order $order)
    {
        $order->update(['status' => 'approved']);
        return $order;
    }

    public function deliver(Order $order)
    {
        $order->update(['status' => 'delivered']);
        return $order;
    }
}