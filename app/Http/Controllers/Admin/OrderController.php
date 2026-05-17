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
     * Alur: Validasi -> Cek Status Lama -> Update -> Potong Stok (jika status berubah ke 'completed').
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
         * 2. Logika Pemotongan Stok (Otomatisasi Stok)
         * Stok hanya dipotong jika status SEBELUMNYA bukan 'completed'
         * dan status BARU adalah 'completed'.
         * Hal ini untuk mencegah 'double-counting' (stok berkurang berkali-kali).
         */
        if ($originalStatus !== "completed" && $newStatus === "completed") {
            $order->load("items.book");
            foreach ($order->items as $item) {
                if ($item->book) {
                    // Eksekusi pengurangan stok: Stok Akhir = Stok Awal - Quantity
                    $item->book->decrement("stock", $item->quantity);
                }
            }
        }

        return redirect()
            ->back()
            ->with("success", "Order status updated successfully.");
    }
}
