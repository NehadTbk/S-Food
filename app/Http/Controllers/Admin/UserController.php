<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::latest();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->search . '%')
                  ->orWhere('last_name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->get();

        return view('admin.users.index', compact('users'));
    }

    public function create(): View
    {
        return view('admin.users.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name'   => ['required', 'string', 'max:255'],
            'last_name'    => ['required', 'string', 'max:255'],
            'email'        => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'phone'        => ['required', 'string', 'max:20'],
            'street'       => ['required', 'string', 'max:255'],
            'house_number' => ['required', 'string', 'max:20'],
            'bus'          => ['nullable', 'string', 'max:20'],
            'postal_code'  => ['required', 'string', 'max:10'],
            'city'         => ['required', 'string', 'max:100'],
            'role'         => ['required', 'in:user,admin,deliverer'],
            'password'     => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        User::create([
            'first_name'   => $request->first_name,
            'last_name'    => $request->last_name,
            'email'        => $request->email,
            'phone'        => $request->phone,
            'street'       => $request->street,
            'house_number' => $request->house_number,
            'bus'          => $request->bus,
            'postal_code'  => $request->postal_code,
            'city'         => $request->city,
            'role'         => $request->role,
            'password'     => Hash::make($request->password),
            'active'       => true,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Gebruiker aangemaakt.');
    }

    public function updateRole(Request $request, User $user): RedirectResponse
    {
        $request->validate(['role' => ['required', 'in:user,admin,deliverer']]);
        $user->update(['role' => $request->role]);
        return back()->with('success', 'Rol van ' . $user->first_name . ' bijgewerkt.');
    }

    public function toggleActive(User $user): RedirectResponse
    {
        // Prevent deactivating yourself
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Je kunt je eigen account niet deactiveren.');
        }
        $user->update(['active' => !$user->active]);
        $state = $user->active ? 'geactiveerd' : 'gedeactiveerd';
        return back()->with('success', $user->first_name . ' is ' . $state . '.');
    }
}
