<?php

namespace App\Http\Requests\Manager\Client;

use App\Rules\MobilePhone;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'email' => 'required|string|email:filter|unique:users,email,' .$this->id.',id,deleted_at,NULL',
            'phone' => [
                'nullable',
                'numeric',
                new MobilePhone,
                'unique:users,phone,' .$this->id.',id,deleted_at,NULL',
            ],
            'ob_status' => 'required|integer',
            'in_process' => 'required|integer',
            'warranty_end' => 'nullable|date',
            'id' => 'required|integer|exists:users,id',
        ]);
    }

    public function messages()
    {
        return $this->commonMessages();
    }
}
