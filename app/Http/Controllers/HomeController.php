<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::with("category");

        if ($request->has("search") && $request->search != "") {
            $query->where("title", "like", "%" . $request->search . "%");
        }

        if ($request->has("category") && $request->category != "") {
            $query->whereHas("category", function ($q) use ($request) {
                $q->where("slug", $request->category);
            });
        }

        if ($request->has("author") && $request->author != "") {
            $query->where("author", "like", "%" . $request->author . "%");
        }

        if ($request->has("min_price") && $request->min_price != "") {
            $query->where("price", ">=", $request->min_price);
        }

        if ($request->has("max_price") && $request->max_price != "") {
            $query->where("price", "<=", $request->max_price);
        }

        // appends($request->all()) to keep filters in pagination links
        $books = $query->paginate(12)->appends($request->all());

        $categories = Category::all();

        return view("welcome", compact("books", "categories"));
    }
}
