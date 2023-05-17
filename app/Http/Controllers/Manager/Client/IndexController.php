<?php

namespace App\Http\Controllers\Manager\Client;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class IndexController extends Controller
{
    public function __invoke($ob_status = '1')
    {
        // Разрешение на просмотр
        if (
            ($ob_status === '2' && !Gate::allows('show_ob_status_2'))
            || ($ob_status === '3' && !Gate::allows('show_ob_status_3'))
        ) {
            return redirect()->route('manager.client.index', 1);
        }

        $clients = User::where('type', 0);
        // В списке объектов также показываем объекты на гарантии
        if ($ob_status === '1' && Gate::allows('show_ob_status_2')) {
            $clients->whereIn('ob_status',[1,2]);
        } else {
            $clients->where('ob_status', $ob_status);
        }
        $clients = $clients->get();

        // Сортируем объекты: сначала в работе, затем на гарантии, затем тестовые
        $demo_objects = 0;
        $ob_status_2_objects = 0;
        if (!empty($clients) && count($clients)) {
            foreach ($clients as &$client) {
                if (in_array($client->id,config('global.arTestAndDemoObjects'))) { // Демо-объекты в самом низу
                    $client->type_order = '30';
                    $demo_objects = 1;
                } elseif ($client->ob_status === 2) { // Объекты на гарантии внизу, над демо-объектами
                    $client->type_order = '20';
                    if ($ob_status === '1') $ob_status_2_objects = 1;
                } else {
                    $client->type_order = '10';
                }
            }
        }

        // Отметка о том, что это страница объектов на гарантии
        $ob_status_2_page = ($ob_status === '2') ? 1 : 0;

        $pageName = match ($ob_status) {
            '1' => 'Объекты',
            '2' => 'Объекты на гарантии',
            '3' => 'Объекты завершённые',
        };

        return view('manager.client.index', compact('clients','pageName', 'demo_objects', 'ob_status_2_objects', 'ob_status_2_page'));
    }
}
