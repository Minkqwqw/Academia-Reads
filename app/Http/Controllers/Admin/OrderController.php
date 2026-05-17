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

    /**
     * Update status pesanan dan mengelola logika stok otomatis.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            "status" => "required|in:pending,processing,completed,cancelled",
        ]);

        $originalStatus = $order->status;
        $newStatus = $request->status;

        // 1. Update status pesanan di database
        $order->update(["status" => $newStatus]);

        /**
         * 2. Logika Pemotongan/Pengembalian Stok
         */
        // Potong stok jika berubah ke 'completed'
        if ($originalStatus !== "completed" && $newStatus === "completed") {
            $order->load("items.book");
            foreach ($order->items as $item) {
                if ($item->book) {
                    $item->book->decrement("stock", $item->quantity);
                }
            }
        }

        // Kembalikan stok jika berubah DARI 'completed' ke status lain
        if ($originalStatus === "completed" && $newStatus !== "completed") {
            $order->load("items.book");
            foreach ($order->items as $item) {
                if ($item->book) {
                    $item->book->increment("stock", $item->quantity);
                }
            }
        }

        return redirect()
            ->back()
            ->with("success", "Order status updated successfully.");
    }

    /**
     * Hapus history pesanan dengan pengembalian stok jika statusnya completed.
     */
    public function destroy(Order $order)
    {
        if ($order->status === "completed") {
            $order->load("items.book");
            foreach ($order->items as $item) {
                if ($item->book) {
                    $item->book->increment("stock", $item->quantity);
                }
            }
        }

        $order->delete();

        return redirect()
            ->route("admin.orders.index")
            ->with("success", "Order history deleted successfully.");
    }
}
