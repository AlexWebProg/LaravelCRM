<?php

namespace App\Http\Controllers\Manager\Settings;

use App\Http\Controllers\Controller;
use App\Models\Settings;

class EditController extends Controller
{
    public function __invoke()
    {
        $settings = Settings::all();
        $arSettings = [];
        foreach ($settings as $setting) {
            $arSettings[$setting->name] = $setting->value;
        }
        return view('manager.settings.edit',compact('arSettings'));
    }
}
