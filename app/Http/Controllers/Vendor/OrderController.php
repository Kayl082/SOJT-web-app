<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | List Orders (Store Isolated Automatically)
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $orders = Order::with(['customer:id,name,email'])
            ->latest()
            ->paginate(10);

        return response()->json($orders);
    }

    /*
    |--------------------------------------------------------------------------
    | Show Single Order
    |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $order = Order::with([
                'customer:id,name,email',
                'items.product:id,product_name,price'
            ])
            ->findOrFail($id);

        return response()->json($order);
    }

    /*
    |--------------------------------------------------------------------------
    | Update Order Status
    |--------------------------------------------------------------------------
    */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => [
                'required',
                'in:confirmed,preparing,ready,completed,cancelled'
            ],
        ]);

        $order = Order::findOrFail($id);

        // Optional: enforce valid status transitions
        $allowedTransitions = [
            'pending' => ['confirmed', 'cancelled'],
            'confirmed' => ['preparing', 'cancelled'],
            'preparing' => ['ready'],
            'ready' => ['completed'],
        ];

        $currentStatus = $order->status;
        $newStatus = $validated['status'];

        if (isset($allowedTransitions[$currentStatus])) {
            if (! in_array($newStatus, $allowedTransitions[$currentStatus])) {
                return response()->json([
                    'message' => 'Invalid status transition.'
                ], 422);
            }
        }

        $order->update([
            'status' => $newStatus
        ]);

        return response()->json([
            'message' => 'Order status updated successfully.'
        ]);
    }
}