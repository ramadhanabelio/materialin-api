<?php

namespace App\Http\Controllers\User;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class OrderUserController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $user = $request->user();
        $total = 0;

        DB::beginTransaction();
        try {
            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                if ($product->stock < $item['quantity']) {
                    return response()->json([
                        'message' => "Stok tidak cukup untuk produk {$product->name}"
                    ], 422);
                }
                $total += $product->price * $item['quantity'];
            }

            $order = Order::create([
                'user_id' => $user->id,
                'total_price' => $total,
                'status' => 'pending',
            ]);

            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                ]);

                $product->stock -= $item['quantity'];
                $product->save();
            }

            DB::commit();

            return response()->json([
                'message' => 'Pesanan berhasil dibuat.',
                'order' => $order->load('items.product')
            ], 201);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['message' => 'Terjadi kesalahan saat memproses pesanan.'], 500);
        }
    }

    public function index(Request $request)
    {
        $orders = $request->user()->orders()->with('items.product')->latest()->get();
        return $orders;
    }

    public function show(Request $request, $id)
    {
        $order = $request->user()->orders()->with('items.product')->find($id);

        if (!$order) {
            return response()->json(['message' => 'Pesanan tidak ditemukan.'], 404);
        }

        return $order;
    }
}
