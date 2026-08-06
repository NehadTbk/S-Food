<?php

namespace App\Http\Controllers;

use App\Models\AboutPage;
use Illuminate\View\View;

class PageController extends Controller
{
    public function about(): View
    {
        $aboutPage = AboutPage::current();

        return view('pages.about', compact('aboutPage'));
    }
}
