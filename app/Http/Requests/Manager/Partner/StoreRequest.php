<?php

namespace App\Http\Requests\Manager\Partner;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return $this->commonRules();
    }

    public function messages()
    {
        return $this->commonMessages();
    }
}
