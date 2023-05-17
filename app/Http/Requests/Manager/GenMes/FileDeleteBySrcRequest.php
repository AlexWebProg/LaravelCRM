<?php

namespace App\Http\Requests\Manager\GenMes;

use Illuminate\Foundation\Http\FormRequest;

class FileDeleteBySrcRequest extends FormRequest
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
            'src' => substr($this->src,strpos($this->src,'chat'))
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
            'gen_mes_id' => 'required|integer|exists:gen_mes,id',
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
