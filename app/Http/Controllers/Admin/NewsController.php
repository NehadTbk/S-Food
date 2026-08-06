<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsPost;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function create(): View
    {
        return view('admin.news.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title'             => ['required', 'string', 'max:255'],
            'content'           => ['required', 'string'],
            'publication_date'  => ['required', 'date'],
            'image'             => ['nullable', 'image', 'max:10240'],
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('news', 'public');
        }

        $newsPost = NewsPost::create($data);

        return redirect()->route('news.show', $newsPost)->with('success', '"' . $newsPost->title . '" toegevoegd.');
    }

    public function edit(NewsPost $newsPost): View
    {
        return view('admin.news.edit', compact('newsPost'));
    }

    public function update(Request $request, NewsPost $newsPost): RedirectResponse
    {
        $data = $request->validate([
            'title'             => ['required', 'string', 'max:255'],
            'content'           => ['required', 'string'],
            'publication_date'  => ['required', 'date'],
            'image'             => ['nullable', 'image', 'max:10240'],
        ]);

        if ($request->hasFile('image')) {
            if ($newsPost->image) {
                Storage::disk('public')->delete($newsPost->image);
            }
            $data['image'] = $request->file('image')->store('news', 'public');
        } else {
            unset($data['image']);
        }

        $newsPost->update($data);

        return redirect()->route('news.show', $newsPost)->with('success', '"' . $newsPost->title . '" bijgewerkt.');
    }

    public function destroy(NewsPost $newsPost): RedirectResponse
    {
        if ($newsPost->image) {
            Storage::disk('public')->delete($newsPost->image);
        }
        $newsPost->delete();

        return redirect()->route('news.index')->with('success', 'Nieuwtje verwijderd.');
    }
}
