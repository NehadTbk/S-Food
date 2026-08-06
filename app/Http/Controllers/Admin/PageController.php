<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutPage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PageController extends Controller
{
    public function editAbout(): View
    {
        $aboutPage = AboutPage::current();

        return view('admin.pages.about-edit', compact('aboutPage'));
    }

    public function updateAbout(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'content' => ['required', 'string'],
            'image'   => ['nullable', 'image', 'max:10240'],
        ]);

        $aboutPage = AboutPage::current();

        if ($request->hasFile('image')) {
            if ($aboutPage->image) {
                Storage::disk('public')->delete($aboutPage->image);
            }
            $data['image'] = $request->file('image')->store('pages', 'public');
        } else {
            unset($data['image']);
        }

        $aboutPage->update($data);

        return redirect()->route('pages.about')->with('success', 'Over ons-pagina bijgewerkt.');
    }
}
