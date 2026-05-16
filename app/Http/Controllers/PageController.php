<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Message;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function showBook(Book $book)
    {
        $book->load("category");
        return view("book.show", compact("book"));
    }

    public function about()
    {
        return view("user.about");
    }

    public function contact()
    {
        return view("user.contact");
    }

    public function storeMessage(Request $request)
    {
        $request->validate([
            "name" => "required|string|max:255",
            "email" => "required|email|max:255",
            "subject" => "required|string|max:255",
            "message" => "required|string",
        ]);

        Message::create($request->all());

        return redirect()
            ->back()
            ->with("success", "Your message has been sent successfully.");
    }
}
