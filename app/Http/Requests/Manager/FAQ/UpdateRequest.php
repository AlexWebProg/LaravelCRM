<?php

namespace App\Http\Requests\Manager\FAQ;

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
        return array_merge($this->commonRules(), [
            'id' => 'required|integer|exists:faq,id',
        ]);
    }

    public function messages()
    {
        return $this->commonMessages();
    }
}
