<?php

namespace App\Http\Controllers\Client\Contact;

use App\Models\Stat;
use App\Models\Contact;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function __invoke()
    {
        Stat::create(['user_id' => auth()->user()->id, 'action' => 'Контакты сотрудников']);
        $contacts = Contact::where('is_active',1)
            ->orderBy('sort','asc')
            ->get();
        return view('client.contact.index',compact('contacts'));
    }
}
