<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    public function show(User $user)
    {
        Gate::authorize('show', $user);

        return view('users.show', compact('user'));
    }
}
