<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\BookController as AdminBookController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\MessageController as AdminMessageController;
use Illuminate\Support\Facades\Route;

Route::get("/", [HomeController::class, "index"])->name("home");
Route::get("/search", [HomeController::class, "index"])->name("books.search");
Route::get("/about", [PageController::class, "about"])->name("about");
Route::get("/contact", [PageController::class, "contact"])->name("contact");
Route::get("/books/{book}", [PageController::class, "showBook"])->name(
    "books.show",
);
Route::post("/contact", [PageController::class, "storeMessage"])->name(
    "messages.store",
);

Route::get("/cart", [CartController::class, "index"])->name("cart.index");
Route::post("/cart/add/{book}", [CartController::class, "add"])->name(
    "cart.add",
);
Route::post("/cart/update/{id}", [CartController::class, "update"])->name(
    "cart.update",
);
Route::post("/cart/remove/{id}", [CartController::class, "remove"])->name(
    "cart.remove",
);

Route::get("/dashboard", function () {
    return view("dashboard");
})
    ->middleware(["auth", "verified"])
    ->name("dashboard");

Route::middleware("auth")->group(function () {
    Route::get("/profile", [ProfileController::class, "edit"])->name(
        "profile.edit",
    );
    Route::patch("/profile", [ProfileController::class, "update"])->name(
        "profile.update",
    );
    Route::delete("/profile", [ProfileController::class, "destroy"])->name(
        "profile.destroy",
    );

    Route::get("/orders", [OrderController::class, "index"])->name(
        "orders.index",
    );
    Route::post("/checkout", [OrderController::class, "store"])->name(
        "checkout",
    );
    Route::patch("/orders/{order}/cancel", [
        OrderController::class,
        "cancel",
    ])->name("orders.cancel");
});

Route::middleware(["auth", "role:admin"])
    ->prefix("admin")
    ->name("admin.")
    ->group(function () {
        Route::get("/dashboard", function () {
            return view("admin.dashboard");
        })->name("dashboard");

        Route::resource("categories", AdminCategoryController::class);
        Route::resource("books", AdminBookController::class);
        Route::resource("users", AdminUserController::class);

        Route::get("orders", [AdminOrderController::class, "index"])->name(
            "orders.index",
        );
        Route::get("orders/{order}", [
            AdminOrderController::class,
            "show",
        ])->name("orders.show");
        Route::patch("orders/{order}/status", [
            AdminOrderController::class,
            "updateStatus",
        ])->name("orders.updateStatus");
        Route::delete("orders/{order}", [
            AdminOrderController::class,
            "destroy",
        ])->name("orders.destroy");

        Route::get("messages", [AdminMessageController::class, "index"])->name(
            "messages.index",
        );
        Route::get("messages/{message}", [
            AdminMessageController::class,
            "show",
        ])->name("messages.show");
    });

require __DIR__ . "/auth.php";
