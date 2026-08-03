<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Allergeen;
use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $categories = Category::withCount('menuItems')->orderBy('name')->get();
        $allergeens = Allergeen::orderBy('name')->get();

        $query = MenuItem::with(['category', 'allergeens'])->latest();

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $menuItems = $query->get();

        return view('admin.dashboard', compact('categories', 'allergeens', 'menuItems'));
    }

    // Category inline actions
    public function storeCategory(Request $request): RedirectResponse
    {
        $request->validate(['name' => ['required', 'string', 'max:100', 'unique:categories,name']]);
        Category::create(['name' => $request->name]);
        return back()->with('success', 'Categorie aangemaakt.');
    }

    public function updateCategory(Request $request, Category $category): RedirectResponse
    {
        $request->validate(['name' => ['required', 'string', 'max:100', 'unique:categories,name,' . $category->id]]);
        $category->update(['name' => $request->name]);
        return back()->with('success', 'Categorie bijgewerkt.');
    }

    public function destroyCategory(Category $category): RedirectResponse
    {
        if ($category->menuItems()->exists()) {
            return back()->with('error', 'Categorie heeft nog gerechten en kan niet verwijderd worden.');
        }
        $category->delete();
        return back()->with('success', 'Categorie verwijderd.');
    }
}
