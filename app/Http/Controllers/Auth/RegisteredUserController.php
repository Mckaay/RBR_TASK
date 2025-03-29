<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;

use function response;

use Session;

final class RegisteredUserController extends Controller
{
    public function store(RegisterRequest $request): \Illuminate\Http\RedirectResponse
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make((string) $request->string('password')),
        ]);

        event(new Registered($user));

        Session::flash('registered');

        return response()->redirectTo(route('login'));
    }
}
