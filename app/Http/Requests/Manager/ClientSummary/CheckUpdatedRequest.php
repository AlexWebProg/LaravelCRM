<?php

namespace App\Http\Requests\Manager\ClientSummary;

use Illuminate\Foundation\Http\FormRequest;

class CheckUpdatedRequest extends FormRequest
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
            'updated_at' => 'required|date_format:Y-m-d H:i:s',
        ];
    }

    public function messages()
    {
        return [
            'updated_at.required' => 'Что-то пошло не так. Попробуйте обновить страницу',
            'updated_at.date_format' => 'Что-то пошло не так. Попробуйте обновить страницу',
        ];
    }
}
