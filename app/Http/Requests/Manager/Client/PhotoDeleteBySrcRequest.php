<?php

namespace App\Http\Requests\Manager\Client;

use Illuminate\Foundation\Http\FormRequest;

class PhotoDeleteBySrcRequest extends FormRequest
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

    protected function prepareForValidation()
    {
        $this->merge([
            'src' => substr($this->src,strpos($this->src,'photo'))
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'src' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'src.required' => 'Не выбраны фото для удаления',
            'src.string' => 'Не выбраны фото для удаления',
        ];
    }
}
