<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | List Products
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $products = Product::latest()->paginate(10);

        return response()->json($products);
    }

    /*
    |--------------------------------------------------------------------------
    | Create Product
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'product_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'barcode' => ['nullable', 'string', 'max:100'],
            'price' => ['required', 'numeric', 'min:0'],
        ]);

        $product = Product::create([
            'store_id' => $request->user()->store_id, // important
            'category_id' => $validated['category_id'],
            'product_name' => $validated['product_name'],
            'description' => $validated['description'] ?? null,
            'barcode' => $validated['barcode'] ?? null,
            'price' => $validated['price'],
            'is_active' => true,
        ]);

        return response()->json($product, 201);
    }

    /*
    |--------------------------------------------------------------------------
    | Update Product Info (Not Price)
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'product_name' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'barcode' => ['nullable', 'string', 'max:100'],
        ]);

        $product->update($validated);

        return response()->json($product);
    }

    /*
    |--------------------------------------------------------------------------
    | Update Stock
    |--------------------------------------------------------------------------
    */
    public function updateStock(Request $request, $id)
    {
        $validated = $request->validate([
            'stock_level' => ['required', 'integer', 'min:0'],
        ]);

        $product = Product::findOrFail($id);

        $product->inventory()->updateOrCreate(
            ['product_id' => $product->id],
            [
                'store_id' => $request->user()->store_id,
                'stock_level' => $validated['stock_level'],
                'is_available' => $validated['stock_level'] > 0,
            ]
        );

        return response()->json(['message' => 'Stock updated']);
    }

    /*
    |--------------------------------------------------------------------------
    | Update Price (Owner Only)
    |--------------------------------------------------------------------------
    */
    public function updatePrice(Request $request, $id)
    {
        $validated = $request->validate([
            'price' => ['required', 'numeric', 'min:0'],
        ]);

        $product = Product::findOrFail($id);

        $product->update([
            'price' => $validated['price'],
        ]);

        return response()->json(['message' => 'Price updated']);
    }
}