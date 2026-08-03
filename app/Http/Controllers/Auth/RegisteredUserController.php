<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name'  => ['required', 'string', 'max:255'],
            'email'      => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone'        => ['required', 'string', 'max:20'],
            'street'       => ['required', 'string', 'max:255'],
            'house_number' => ['required', 'string', 'max:20'],
            'bus'          => ['nullable', 'string', 'max:20'],
            'postal_code'  => ['required', 'string', 'max:10'],
            'city'         => ['required', 'string', 'max:100'],
            'password'   => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'email'      => $request->email,
            'phone'        => $request->phone,
            'street'       => $request->street,
            'house_number' => $request->house_number,
            'bus'          => $request->bus,
            'postal_code'  => $request->postal_code,
            'city'         => $request->city,
            'password'     => Hash::make($request->password),
            'role'     => 'user',
            'active'   => true,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect('/');
    }
}
