<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller
{
    /**
     * Display store orders
     */
    public function index(Request $request)
    {
        $storeId = $request->user()->store_id;

        $orders = Order::where('store_id', $storeId)
            ->with('customer')
            ->latest()
            ->get();

        return Inertia::render('vendor/Orders/Index', [
            'orders' => $orders
        ]);
    }

    /**
     * Show order details
     */
    public function show(Order $order, Request $request)
    {
        if ($order->store_id !== $request->user()->store_id) {
            abort(403);
        }

        $order->load(['customer', 'items.inventory.product']);

        return Inertia::render('vendor/Orders/Show', [
            'order' => $order
        ]);
    }

    /**
     * Update order status
     */
    public function updateStatus(Order $order, Request $request)
    {
        if ($order->store_id !== $request->user()->store_id) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => ['required', 'in:confirmed,preparing,ready,completed']
        ]);

        $order->update([
            'status' => $validated['status']
        ]);

        return back()->with('success', 'Order status updated.');
    }
}