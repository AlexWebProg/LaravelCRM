<?php

namespace App\Http\Controllers\Manager\Client;

use App\Http\Requests\Manager\Client\TaskFileDeleteBySrcRequest;
use App\Models\TaskFiles;

class TaskFileDeleteBySrcController extends BaseController
{
    public function __invoke(TaskFileDeleteBySrcRequest $request)
    {
        $data = $request->validated();
        if (!empty($data['src'])) {
            $task_file = TaskFiles::where('src',$data['src'])->first();
            if (!empty($task_file)) {
                @unlink(storage_path('app/public/'.$data['src']));
                $task_file->delete();
                return response(['result'=> 1],200);
            }
        }
        return response(['result'=> 0],200);
    }
}
