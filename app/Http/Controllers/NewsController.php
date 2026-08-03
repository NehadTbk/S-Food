<?php

namespace App\Http\Controllers;

use App\Models\NewsPost;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function index(): View
    {
        $newsPosts = NewsPost::orderByDesc('publication_date')->paginate(9);

        return view('news.index', compact('newsPosts'));
    }

    public function show(NewsPost $newsPost): View
    {
        return view('news.show', compact('newsPost'));
    }
}
