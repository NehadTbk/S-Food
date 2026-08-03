<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Allergeen;
use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MenuItemController extends Controller
{
    public function create(): View
    {
        $categories = Category::orderBy('name')->get();
        $allergeens = Allergeen::orderBy('name')->get();
        return view('admin.menu.create', compact('categories', 'allergeens'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'              => ['required', 'string', 'max:255'],
            'category_id'       => ['required', 'exists:categories,id'],
            'short_description' => ['required', 'string', 'max:255'],
            'full_description'  => ['nullable', 'string'],
            'ingredients'       => ['nullable', 'string'],
            'price'             => ['required', 'numeric', 'min:0'],
            'type'              => ['required', 'in:fixed,daily_special'],
            'available_on'      => ['nullable', 'date', 'required_if:type,daily_special'],
            'active'            => ['boolean'],
            'photo'             => ['nullable', 'image', 'max:2048'],
            'allergeens'        => ['nullable', 'array'],
            'allergeens.*'      => ['exists:allergeens,id'],
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        }

        $data['active'] = $request->boolean('active', true);

        $item = MenuItem::create($data);
        $item->allergeens()->sync($request->input('allergeens', []));

        return redirect()->route('admin.dashboard')->with('success', '"' . $item->name . '" toegevoegd.');
    }

    public function edit(MenuItem $menuItem): View
    {
        $categories = Category::orderBy('name')->get();
        $allergeens = Allergeen::orderBy('name')->get();
        return view('admin.menu.edit', compact('menuItem', 'categories', 'allergeens'));
    }

    public function update(Request $request, MenuItem $menuItem): RedirectResponse
    {
        $data = $request->validate([
            'name'              => ['required', 'string', 'max:255'],
            'category_id'       => ['required', 'exists:categories,id'],
            'short_description' => ['required', 'string', 'max:255'],
            'full_description'  => ['nullable', 'string'],
            'ingredients'       => ['nullable', 'string'],
            'price'             => ['required', 'numeric', 'min:0'],
            'type'              => ['required', 'in:fixed,daily_special'],
            'available_on'      => ['nullable', 'date', 'required_if:type,daily_special'],
            'active'            => ['boolean'],
            'photo'             => ['nullable', 'image', 'max:2048'],
            'allergeens'        => ['nullable', 'array'],
            'allergeens.*'      => ['exists:allergeens,id'],
        ]);

        if ($request->hasFile('photo')) {
            if ($menuItem->photo) {
                Storage::disk('public')->delete($menuItem->photo);
            }
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        } else {
            unset($data['photo']);
        }

        $data['active'] = $request->boolean('active');

        $menuItem->update($data);
        $menuItem->allergeens()->sync($request->input('allergeens', []));

        return redirect()->route('admin.dashboard')->with('success', '"' . $menuItem->name . '" bijgewerkt.');
    }

    public function destroy(MenuItem $menuItem): RedirectResponse
    {
        if ($menuItem->photo) {
            Storage::disk('public')->delete($menuItem->photo);
        }
        $menuItem->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Gerecht verwijderd.');
    }

    public function toggle(MenuItem $menuItem): RedirectResponse
    {
        $menuItem->update(['active' => !$menuItem->active]);
        return back()->with('success', '"' . $menuItem->name . '" is nu ' . ($menuItem->active ? 'actief' : 'inactief') . '.');
    }
}
