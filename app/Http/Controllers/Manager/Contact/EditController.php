<?php

namespace App\Http\Controllers\Manager\Contact;

use App\Http\Controllers\Controller;
use App\Models\Contact;

class EditController extends Controller
{
    public function __invoke(Contact $contact)
    {
        return view('manager.contact.edit', compact('contact'));
    }
}
