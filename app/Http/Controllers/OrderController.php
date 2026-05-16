<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Auth::user()
            ->orders()
            ->with("items.book")
            ->latest()
            ->paginate(10);
        return view("orders.index", compact("orders"));
    }

    public function store(Request $request)
    {
        $request->validate([
            "shipping_address" => "required|string",
        ]);

        $cart = session()->get("cart", []);

        if (empty($cart)) {
            return redirect()
                ->route("cart.index")
                ->with("error", "Your cart is empty.");
        }

        $totalPrice = 0;
        foreach ($cart as $item) {
            $totalPrice += $item["price"] * $item["quantity"];
        }

        $order = Order::create([
            "user_id" => Auth::id(),
            "shipping_address" => $request->shipping_address,
            "payment_method" => "COD",
            "total_price" => $totalPrice,
            "status" => "pending",
        ]);

        foreach ($cart as $item) {
            OrderItem::create([
                "order_id" => $order->id,
                "book_id" => $item["id"],
                "quantity" => $item["quantity"],
                "price" => $item["price"],
            ]);
        }

        session()->forget("cart");

        return redirect()
            ->route("orders.index")
            ->with(
                "success",
                "Order placed successfully! Please pay at delivery.",
            );
    }

    public function cancel(Request $request, Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if ($order->status === "pending") {
            $order->update(["status" => "cancelled"]);
            return redirect()
                ->back()
                ->with("success", "Order cancelled successfully.");
        }

        return redirect()
            ->back()
            ->with("error", "Only pending orders can be cancelled.");
    }
}
