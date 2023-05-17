<?php

namespace App\Http\Requests\Manager\ExpensesObject;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEstimateRequest extends FormRequest
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
            'expense_estimate_sum_1_string' => 'nullable|string',
            'expense_estimate_sum_1' => 'nullable|numeric',
            'expense_estimate_sum_2_string' => 'nullable|string',
            'expense_estimate_sum_2' => 'nullable|numeric',
        ];
    }

    public function messages()
    {
        return [
            'expense_estimate_sum_1.numeric' => 'Это поле должно быть указано числом в формате 0.00',
            'expense_estimate_sum_2.numeric' => 'Это поле должно быть указано числом в формате 0.00',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'expense_estimate_sum_1' => !empty($this->expense_estimate_sum_1) ? (int) (floatval(str_replace([' ',','], ['','.'], $this->expense_estimate_sum_1)) * 100) : null,
            'expense_estimate_sum_2' => !empty($this->expense_estimate_sum_2) ? (int) (floatval(str_replace([' ',','], ['','.'], $this->expense_estimate_sum_2)) * 100) : null,
        ]);
    }

}
