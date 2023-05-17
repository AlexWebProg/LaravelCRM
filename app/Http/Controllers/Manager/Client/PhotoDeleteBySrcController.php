<?php

namespace App\Http\Controllers\Manager\Client;

use App\Http\Requests\Manager\Client\PhotoDeleteBySrcRequest;
use App\Models\Photo;
use Illuminate\Support\Facades\Gate;

class PhotoDeleteBySrcController extends BaseController
{
    public function __invoke(PhotoDeleteBySrcRequest $request)
    {
        $data = $request->validated();
        if (!empty($data['src'])) {
            $photo = Photo::where('src',$data['src'])->first();
            if (!empty($photo) && Gate::allows('delete-photo', $photo)) {
                @unlink(storage_path('app/public/'.$data['src']));
                $photo->delete();
                return response(['result'=> 1],200);
            }
        }
        return response(['result'=> 0],200);
    }
}
