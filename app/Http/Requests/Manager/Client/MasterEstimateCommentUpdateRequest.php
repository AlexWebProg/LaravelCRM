<?php

namespace App\Http\Requests\Manager\Client;

use Illuminate\Foundation\Http\FormRequest;

class MasterEstimateCommentUpdateRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'master_estimate_comment' => 'sometimes|nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'master_estimate_comment.string' => 'Это поле должно быть текстом',
        ];
    }
}
