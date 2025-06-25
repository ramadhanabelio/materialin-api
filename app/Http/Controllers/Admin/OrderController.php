<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')->latest()->get();

        return response()->json($orders);
    }

    public function show($id)
    {
        $order = Order::with(['user', 'items.product'])->find($id);

        if (!$order) {
            return response()->json(['message' => 'Pesanan tidak ditemukan.'], 404);
        }

        return response()->json($order);
    }
}
