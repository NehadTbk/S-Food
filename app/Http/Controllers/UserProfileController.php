<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class UserProfileController extends Controller
{
    public function show(string $username): View
    {
        // Find by username, or fall back to id
        $user = is_numeric($username)
            ? User::findOrFail($username)
            : User::where('username', $username)->firstOrFail();

        return view('profile.show', compact('user'));
    }

    public function update(Request $request, string $username): RedirectResponse
    {
        $user = is_numeric($username)
            ? User::findOrFail($username)
            : User::where('username', $username)->firstOrFail();

        // Only the owner can update
        abort_unless(auth()->id() === $user->id, 403);

        $request->validate([
            'username' => ['nullable', 'string', 'max:50', Rule::unique('users')->ignore($user->id)],
            'birthday' => ['nullable', 'date', 'before:today'],
            'bio'      => ['nullable', 'string', 'max:500'],
            'photo'    => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($user->photo && file_exists(storage_path('app/public/' . $user->photo))) {
                unlink(storage_path('app/public/' . $user->photo));
            }
            $path = $request->file('photo')->store('photos', 'public');
            $user->photo = $path;
        }

        $user->username = $request->username ?: null;
        $user->birthday = $request->birthday ?: null;
        $user->bio      = $request->bio ?: null;
        $user->save();

        $redirect = $user->username ?? $user->id;

        return redirect("/profiel/{$redirect}")->with('success', 'Profiel bijgewerkt.');
    }
}
