<?php

namespace App\Http\Controllers;

use App\Models\FaqCategory;
use Illuminate\View\View;

class FaqController extends Controller
{
    public function index(): View
    {
        $faqCategories = FaqCategory::with(['faqItems' => function ($query) {
            $query->orderBy('created_at');
        }])->orderBy('created_at')->get();

        return view('faq.index', compact('faqCategories'));
    }
}
