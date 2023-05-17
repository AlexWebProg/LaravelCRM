<?php

namespace App\Http\Requests\Manager\ExpensesPersonal;

use Illuminate\Foundation\Http\FormRequest;

class DeleteRequest extends FormRequest
{
    use CommonStoreUpdateData;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'manager_id' => 'required|integer',
            'page' => 'required|string',
        ];
    }

    public function messages()
    {
        return $this->commonMessages();
    }
}
