<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function index()
    {
        /**
         * Mengambil data buku dengan Eager Loading 'category'.
         * Optimasi: Mencegah masalah N+1 Query.
         * Hanya menjalankan 2 query meskipun data buku berjumlah banyak.
         */
        $books = Book::with("category")->paginate(10);
        return view("admin.books.index", compact("books"));
    }

    public function create()
    {
        $categories = Category::all();
        return view("admin.books.create", compact("categories"));
    }

    public function store(Request $request)
    {
        $request->validate([
            "title" => "required|string|max:255",
            "author" => "required|string|max:255",
            "category_id" => "required|exists:categories,id",
            "price" => "required|numeric|min:0",
            "stock" => "required|integer|min:0",
            "description" => "required|string",
            "cover_image" => "required|image|mimes:jpeg,png,jpg|max:4048",
        ]);

        $coverImagePath = $request
            ->file("cover_image")
            ->store("covers", "public");

        Book::create([
            "title" => $request->title,
            "author" => $request->author,
            "category_id" => $request->category_id,
            "price" => $request->price,
            "stock" => $request->stock,
            "description" => $request->description,
            "cover_image" => $coverImagePath,
        ]);

        return redirect()
            ->route("admin.books.index")
            ->with("success", "Book created successfully.");
    }

    public function edit(Book $book)
    {
        $categories = Category::all();
        return view("admin.books.edit", compact("book", "categories"));
    }

    public function update(Request $request, Book $book)
    {
        $request->validate([
            "title" => "required|string|max:255",
            "author" => "required|string|max:255",
            "category_id" => "required|exists:categories,id",
            "price" => "required|numeric|min:0",
            "stock" => "required|integer|min:0",
            "description" => "required|string",
            "cover_image" => "nullable|image|mimes:jpeg,png,jpg,gif|max:2048",
        ]);

        $data = [
            "title" => $request->title,
            "author" => $request->author,
            "category_id" => $request->category_id,
            "price" => $request->price,
            "stock" => $request->stock,
            "description" => $request->description,
        ];

        if ($request->hasFile("cover_image")) {
            if ($book->cover_image) {
                Storage::disk("public")->delete($book->cover_image);
            }
            $data["cover_image"] = $request
                ->file("cover_image")
                ->store("covers", "public");
        }

        $book->update($data);

        return redirect()
            ->route("admin.books.index")
            ->with("success", "Book updated successfully.");
    }

    public function destroy(Book $book)
    {
        if ($book->cover_image) {
            Storage::disk("public")->delete($book->cover_image);
        }
        $book->delete();
        return redirect()
            ->route("admin.books.index")
            ->with("success", "Book deleted successfully.");
    }
}
