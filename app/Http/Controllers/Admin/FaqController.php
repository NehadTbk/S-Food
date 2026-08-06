<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FaqCategory;
use App\Models\FaqItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function storeCategory(Request $request): RedirectResponse
    {
        $request->validate(['name' => ['required', 'string', 'max:100', 'unique:faq_categories,name']]);
        FaqCategory::create(['name' => $request->name]);
        return back()->with('success', 'Categorie aangemaakt.');
    }

    public function updateCategory(Request $request, FaqCategory $faqCategory): RedirectResponse
    {
        $request->validate(['name' => ['required', 'string', 'max:100', 'unique:faq_categories,name,' . $faqCategory->id]]);
        $faqCategory->update(['name' => $request->name]);
        return back()->with('success', 'Categorie bijgewerkt.');
    }

    public function destroyCategory(FaqCategory $faqCategory): RedirectResponse
    {
        if ($faqCategory->faqItems()->exists()) {
            return back()->with('error', 'Categorie heeft nog vragen en kan niet verwijderd worden.');
        }
        $faqCategory->delete();
        return back()->with('success', 'Categorie verwijderd.');
    }

    public function storeItem(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'faq_category_id' => ['required', 'exists:faq_categories,id'],
            'question'        => ['required', 'string', 'max:255'],
            'answer'          => ['required', 'string'],
        ]);
        FaqItem::create($data);
        return back()->with('success', 'Vraag toegevoegd.');
    }

    public function updateItem(Request $request, FaqItem $faqItem): RedirectResponse
    {
        $data = $request->validate([
            'question' => ['required', 'string', 'max:255'],
            'answer'   => ['required', 'string'],
        ]);
        $faqItem->update($data);
        return back()->with('success', 'Vraag bijgewerkt.');
    }

    public function destroyItem(FaqItem $faqItem): RedirectResponse
    {
        $faqItem->delete();
        return back()->with('success', 'Vraag verwijderd.');
    }
}
