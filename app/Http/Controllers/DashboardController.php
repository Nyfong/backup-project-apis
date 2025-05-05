<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalIncome = Order::where('status', 'delivered')->sum('total_amount');
        $orderHistory = Order::with('items.product')->get();

        return response()->json([
            'total_income' => $totalIncome,
            'order_history' => $orderHistory,
        ]);
    }
}