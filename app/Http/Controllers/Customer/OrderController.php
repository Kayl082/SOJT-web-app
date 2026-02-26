<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller
{
    /**
     * Display list of customer orders
     */
    public function index(Request $request)
    {
        $orders = Order::where('customer_id', $request->user()->id)
            ->with('store')
            ->latest()
            ->get();

        return Inertia::render('customer/Orders/Index', [
            'orders' => $orders
        ]);
    }

    /**
     * Display single order details
     */
    public function show(Order $order, Request $request)
    {
        if ($order->customer_id !== $request->user()->id) {
            abort(403);
        }

        $order->load(['store', 'items.inventory.product']);

        return Inertia::render('customer/Orders/Show', [
            'order' => $order
        ]);
    }

    /**
     * Cancel order (if status allows)
     */
    public function cancel(Order $order, Request $request)
    {
        if ($order->customer_id !== $request->user()->id) {
            abort(403);
        }

        if (! in_array($order->status, ['pending', 'confirmed'])) {
            return back()->withErrors(['Cannot cancel this order.']);
        }

        $order->update([
            'status' => 'cancelled'
        ]);

        return back()->with('success', 'Order cancelled.');
    }

    /**
     * Checkout handled in CartController
     */
}