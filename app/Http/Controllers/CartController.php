<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

// Session sementara di RAM
class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get("cart", []);
        return view("cart.index", compact("cart"));
    }

    public function add(Request $request, Book $book)
    {
        $cart = session()->get("cart", []);

        // Optional: you could validate that added amount + current amount <= stock here,
        // but for a simple cart, we just add.
        if (isset($cart[$book->id])) {
            $cart[$book->id]["quantity"]++;
        } else {
            $cart[$book->id] = [
                "id" => $book->id,
                "title" => $book->title,
                "quantity" => 1,
                "price" => $book->price,
                "cover_image" => $book->cover_image,
            ];
        }

        session()->put("cart", $cart);
        return redirect()
            ->back()
            ->with("success", "Book added to cart successfully!");
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            "quantity" => "required|integer|min:1",
        ]);

        $cart = session()->get("cart");

        if (isset($cart[$id])) {
            // Re-check book stock just to be safe
            $book = Book::find($id);
            if ($book && $request->quantity > $book->stock) {
                return redirect()
                    ->route("cart.index")
                    ->with(
                        "error",
                        "Only {$book->stock} items available for '{$book->title}'.",
                    );
            }

            $cart[$id]["quantity"] = $request->quantity;
            session()->put("cart", $cart);
            return redirect()
                ->route("cart.index")
                ->with("success", "Cart updated successfully.");
        }

        return redirect()
            ->route("cart.index")
            ->with("error", "Item not found in cart.");
    }

    public function remove(Request $request, $id)
    {
        if ($id) {
            $cart = session()->get("cart");
            if (isset($cart[$id])) {
                unset($cart[$id]);
                session()->put("cart", $cart);
            }
            return redirect()
                ->route("cart.index")
                ->with("success", "Book removed from cart successfully");
        }
    }
}
