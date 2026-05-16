<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(10);
        return view("admin.users.index", compact("users"));
    }

    public function create()
    {
        return view("admin.users.create");
    }

    public function store(Request $request)
    {
        $request->validate([
            "name" => ["required", "string", "max:255"],
            "email" => [
                "required",
                "string",
                "lowercase",
                "email",
                "max:255",
                "unique:" . User::class,
            ],
            "password" => [
                "required",
                "confirmed",
                \Illuminate\Validation\Rules\Password::defaults(),
            ],
            "role" => ["required", "in:user,admin"],
        ]);

        User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => \Illuminate\Support\Facades\Hash::make(
                $request->password,
            ),
            "role" => $request->role,
        ]);

        return redirect()
            ->route("admin.users.index")
            ->with("success", "User created successfully.");
    }

    public function edit(User $user)
    {
        return view("admin.users.edit", compact("user"));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            "name" => ["required", "string", "max:255"],
            "email" => [
                "required",
                "string",
                "lowercase",
                "email",
                "max:255",
                \Illuminate\Validation\Rule::unique(User::class)->ignore(
                    $user->id,
                ),
            ],
            "role" => ["required", "in:user,admin"],
        ]);

        $data = [
            "name" => $request->name,
            "email" => $request->email,
            "role" => $request->role,
        ];

        if ($request->filled("password")) {
            $request->validate([
                "password" => [
                    "confirmed",
                    \Illuminate\Validation\Rules\Password::defaults(),
                ],
            ]);
            $data["password"] = \Illuminate\Support\Facades\Hash::make(
                $request->password,
            );
        }

        $user->update($data);

        return redirect()
            ->route("admin.users.index")
            ->with("success", "User updated successfully.");
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()
                ->route("admin.users.index")
                ->with("error", "You cannot delete yourself.");
        }

        $user->delete();
        return redirect()
            ->route("admin.users.index")
            ->with("success", "User deleted successfully.");
    }
}
