<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Create Order (Customer)
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $customer = $request->user();

        $validated = $request->validate([
            'store_id' => ['required', 'exists:stores,id'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'pickup_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        return DB::transaction(function () use ($validated, $customer) {

            $total = 0;

            // Validate inventory & calculate total
            foreach ($validated['items'] as $item) {

                $inventory = Inventory::where('store_id', $validated['store_id'])
                    ->where('product_id', $item['product_id'])
                    ->where('is_available', true)
                    ->first();

                if (! $inventory) {
                    abort(422, 'Product not available in this store.');
                }

                if ($inventory->stock_level < $item['quantity']) {
                    abort(422, 'Insufficient stock for product ID: ' . $item['product_id']);
                }

                $total += $inventory->unit_price * $item['quantity'];
            }

            // Create order
            $order = Order::create([
                'customer_id' => $customer->id,
                'store_id' => $validated['store_id'],
                'order_number' => strtoupper(Str::random(10)),
                'status' => 'pending',
                'total_amount' => $total,
                'pickup_date' => $validated['pickup_date'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'is_paid' => false,
            ]);

            // Create order items + deduct stock
            foreach ($validated['items'] as $item) {

                $inventory = Inventory::where('store_id', $validated['store_id'])
                    ->where('product_id', $item['product_id'])
                    ->first();

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $inventory->unit_price,
                    'subtotal' => $inventory->unit_price * $item['quantity'],
                ]);

                // Deduct stock
                $inventory->decrement('stock_level', $item['quantity']);
            }

            return response()->json([
                'message' => 'Order placed successfully.',
                'order' => $order
            ], 201);
        });
    }
}