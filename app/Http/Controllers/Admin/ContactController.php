<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ContactMessageReply;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function index(): View
    {
        $contactMessages = ContactMessage::latest('sent_at')->paginate(15);

        return view('admin.contact.index', compact('contactMessages'));
    }

    public function show(ContactMessage $contactMessage): View
    {
        return view('admin.contact.show', compact('contactMessage'));
    }

    public function reply(Request $request, ContactMessage $contactMessage): RedirectResponse
    {
        $data = $request->validate([
            'reply' => ['required', 'string', 'max:2000'],
        ]);

        $contactMessage->update([
            'reply'      => $data['reply'],
            'replied_at' => now(),
        ]);

        Mail::to($contactMessage->email)->send(new ContactMessageReply($contactMessage));

        return redirect()->route('admin.contact.show', $contactMessage)->with('success', 'Antwoord verstuurd.');
    }
}
