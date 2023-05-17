<?php

namespace App\Http\Requests\Manager\ExpensesObject;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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

    public function rules()
    {
        return $this->commonRules();
    }

    public function messages()
    {
        return $this->commonMessages();
    }
}
