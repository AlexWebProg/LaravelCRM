<?php

namespace App\Http\Requests\Manager\Calls;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'date' => 'required|date_format:Y-m-d',
            'repair_full' => 'nullable|integer',
            'repair_partial' => 'nullable|integer',
            'advertising' => 'nullable|integer',
            'evening_calls' => 'nullable|integer',
            'day_total' => 'nullable|integer',
            'signed_up' => 'nullable|integer',
            'est_wo_dep' => 'nullable|integer',
            'from_youtube' => 'nullable|integer',
            'from_dzen' => 'nullable|integer',
            'from_rutube' => 'nullable|integer',
            'from_telegram' => 'nullable|integer',
            'from_tiktok' => 'nullable|integer',
            'from_vk' => 'nullable|integer',
            'from_site' => 'nullable|integer',
            'from_people' => 'nullable|integer',
            'from_other' => 'nullable|integer',
            'manager_id' => 'nullable|integer',
        ];
    }

    public function messages()
    {
        return [
            'date.required' => 'Что-то пошло не так. Попробуйте обновить страницу',
            'date.date_format' => 'Что-то пошло не так. Попробуйте обновить страницу',

            'repair_full.integer' => 'Это поле должно быть цифрой',
            'repair_partial.integer' => 'Это поле должно быть цифрой',
            'advertising.integer' => 'Это поле должно быть цифрой',
            'evening_calls.integer' => 'Это поле должно быть цифрой',
            'day_total.integer' => 'Это поле должно быть цифрой',
            'signed_up.integer' => 'Это поле должно быть цифрой',
            'est_wo_dep.integer' => 'Это поле должно быть цифрой',
            'from_youtube.integer' => 'Это поле должно быть цифрой',
            'from_dzen.integer' => 'Это поле должно быть цифрой',
            'from_rutube.integer' => 'Это поле должно быть цифрой',
            'from_telegram.integer' => 'Это поле должно быть цифрой',
            'from_tiktok.integer' => 'Это поле должно быть цифрой',
            'from_vk.integer' => 'Это поле должно быть цифрой',
            'from_site.integer' => 'Это поле должно быть цифрой',
            'from_people.integer' => 'Это поле должно быть цифрой',
            'from_other.integer' => 'Это поле должно быть цифрой',

            'manager_id.integer' => 'Что-то пошло не так. Попробуйте обновить страницу',
        ];
    }
}
