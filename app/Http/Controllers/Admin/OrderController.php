<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with("user")->latest()->paginate(10);
        return view("admin.orders.index", compact("orders"));
    }

    public function show(Order $order)
    {
        $order->load(["user", "items.book"]);
        return view("admin.orders.show", compact("order"));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            "status" => "required|in:pending,processing,completed,cancelled",
        ]);

        $originalStatus = $order->status;
        $newStatus = $request->status;

        $order->update(["status" => $newStatus]);

        if ($originalStatus !== "completed" && $newStatus === "completed") {
            $order->load("items.book");
            foreach ($order->items as $item) {
                if ($item->book) {
                    $item->book->decrement("stock", $item->quantity);
                }
            }
        }

        return redirect()
            ->back()
            ->with("success", "Order status updated successfully.");
    }
}
