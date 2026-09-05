<?php

namespace App\Http\Controllers;


use App\Http\Requests\CreateContactRequest;
use App\Models\Contact;
use Illuminate\Http\RedirectResponse;

class ContactController extends Controller
{
    public function store(CreateContactRequest $request): RedirectResponse
    {
        $data = $request->validated();

        Contact::create($data);

        // Return to the form so the sender sees the confirmation
        // where they submitted it.
        return redirect()
            ->route(__('route.contact'))
            ->with('status', __('form.sent'));
    }

    public function destroy(Contact $contact): RedirectResponse
    {
        $contact->delete();

        return redirect()->back();
    }
}
