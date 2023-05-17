<?php

namespace App\Http\Requests\Manager\Manager;

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
            'id' => 'required|integer|exists:users,id',

            'show_ob_status_2' => 'nullable|in:1',
            'show_ob_status_3' => 'nullable|in:1',

            'show_webcam' => 'nullable|in:1',
            'edit_webcam' => 'nullable|in:1',

            'show_photo' => 'nullable|in:1',
            'edit_photo' => 'nullable|in:1',

            'show_plan' => 'nullable|in:1',
            'edit_task' => 'nullable|in:1',
            'delete_task' => 'nullable|in:1',

            'show_estimate' => 'nullable|in:1',
            'edit_estimate' => 'nullable|in:1',

            'show_master_estimate' => 'nullable|in:1',
            'edit_master_estimate' => 'nullable|in:1',

            'show_chat' => 'nullable|in:1',
            'edit_chat' => 'nullable|in:1',

            'show_tech_doc' => 'nullable|in:1',
            'edit_tech_doc' => 'nullable|in:1',
            'delete_tech_doc' => 'nullable|in:1',

            'show_expenses_object' => 'nullable|in:1',
            'edit_expenses_object' => 'nullable|in:1',
            'edit_expenses_object_all' => 'nullable|in:1',
            'edit_expenses_object_estimate' => 'nullable|in:1',

            'show_expenses_personal' => 'nullable|in:1',
            'create_expense_income' => 'nullable|in:1',
            'show_all_expenses_personal' => 'nullable|in:1',
            'edit_all_expenses_personal' => 'nullable|in:1',

            'show_expenses_mat_report' => 'nullable|in:1',

            'show_calls' => 'nullable|in:1',
            'edit_calls' => 'nullable|in:1',

            'show_quiz' => 'nullable|in:1',
            'create_survey' => 'nullable|in:1',
            'edit_quiz' => 'nullable|in:1',
        ]);
    }

    public function messages()
    {
        return $this->commonMessages();
    }
}
