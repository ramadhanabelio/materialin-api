<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function index()
    {
        return Product::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'image'       => 'nullable|string',
        ]);

        $product = Product::create($request->all());

        return response()->json([
            'message' => 'Produk berhasil ditambahkan.',
            'product' => $product
        ], 201);
    }

    public function show($id)
    {
        $product = Product::find($id);
        if (!$product) return response()->json(['message' => 'Produk tidak ditemukan.'], 404);

        return $product;
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        if (!$product) return response()->json(['message' => 'Produk tidak ditemukan.'], 404);

        $product->update($request->all());

        return response()->json([
            'message' => 'Produk berhasil diperbarui.',
            'product' => $product
        ]);
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        if (!$product) return response()->json(['message' => 'Produk tidak ditemukan.'], 404);

        $product->delete();

        return response()->json(['message' => 'Produk berhasil dihapus.']);
    }
}
