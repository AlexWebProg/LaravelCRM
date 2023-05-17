<?php

namespace App\Http\Requests\Manager\Partner;

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

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $strPhone = preg_replace('/[^[:digit:]]/', '', $this->phone);
        if (str_starts_with($strPhone, '8')) {
            $strPhone = '7'.substr($strPhone, 1);
        }
        $this->merge([
            'phone' => $strPhone
        ]);
    }

    public function rules()
    {
        return array_merge($this->commonRules(), [
            'id' => 'required|integer|exists:partners,id',
        ]);
    }

    public function messages()
    {
        return $this->commonMessages();
    }
}
