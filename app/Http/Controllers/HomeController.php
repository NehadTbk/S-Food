<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $categories = Category::orderBy('name')->get();

        $menuItems = MenuItem::with(['category', 'allergeens'])
            ->where(function ($query) {
                $query->where('type', 'fixed')
                      ->where('active', true);
            })
            ->orWhere(function ($query) {
                $query->where('type', 'daily_special')
                      ->where('available_on', today())
                      ->where('active', true);
            })
            ->get();

        $dailySpecials = $menuItems->where('type', 'daily_special');
        $regularItems  = $menuItems->where('type', 'fixed');

        return view('home.index', compact('categories', 'dailySpecials', 'regularItems'));
    }
}
