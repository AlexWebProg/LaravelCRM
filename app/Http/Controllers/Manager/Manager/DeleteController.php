<?php

namespace App\Http\Controllers\Manager\Manager;

use App\Http\Controllers\Controller;
use App\Models\User;

class DeleteController extends Controller
{
    public function __invoke(User $manager)
    {
        $manager->delete();
        return redirect()
            ->route('manager.manager.index', $manager->is_admin)
            ->with('notification',[
                'class' => 'success',
                'message' => 'Пользователь удалён'
            ]);
    }
}
