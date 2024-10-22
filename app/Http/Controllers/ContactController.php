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

        return redirect()->route(__('route.home'));
    }
}
