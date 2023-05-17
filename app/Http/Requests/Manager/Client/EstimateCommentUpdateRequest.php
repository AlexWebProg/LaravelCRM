<?php

namespace App\Http\Requests\Manager\Client;

use Illuminate\Foundation\Http\FormRequest;

class EstimateCommentUpdateRequest extends FormRequest
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
            'estimate_comment' => 'sometimes|nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'estimate_comment.string' => 'Это поле должно быть текстом',
        ];
    }
}
