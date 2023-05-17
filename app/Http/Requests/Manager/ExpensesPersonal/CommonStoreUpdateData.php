<?php
namespace App\Http\Requests\Manager\ExpensesPersonal;

trait CommonStoreUpdateData
{

    protected function commonRules()
    {
        return [
            'date' => 'nullable|date',
            'category' => 'required|integer',
            'transfer_to' => 'required_if:category,4|nullable|integer',
            'sum_string' => 'nullable|string',
            'sum' => 'nullable|numeric',
            'comment' => 'nullable|string',
            'manager_id' => 'required|integer',
            'page' => 'required|string',
        ];
    }

    protected function commonMessages()
    {
        return [
            'date.date' => 'Это поле должно быть датой в формате дд.мм.гггг',
            'category.required' => 'По неизвестной причине поле не заполнено. Попробуйте обновить страницу',
            'category.integer' => 'Это поле заполнено неверно',
            'transfer_to.required_if' => 'Выберите сотрудника',
            'transfer_to.integer' => 'Это поле заполнено неверно',
            'sum_string.string' => 'Это поле должно быть указано числом в формате 0.00',
            'sum.numeric' => 'Это поле должно быть указано числом в формате 0.00',
            'comment.string' => 'Это поле должно быть текстом',
            'manager_id.required' => 'Произошла неизвестная ошибка. Попробуйте обновить страницу',
            'manager_id.integer' => 'Произошла неизвестная ошибка. Попробуйте обновить страницу',
            'page.required' => 'Произошла неизвестная ошибка. Попробуйте обновить страницу',
            'page.string' => 'Произошла неизвестная ошибка. Попробуйте обновить страницу',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'date' => !empty($this->date) ? date('Y-m-d',strtotime($this->date)) : null,
            'sum' => !empty($this->sum) ? (int) (floatval(str_replace([' ',','], ['','.'], $this->sum)) * 100) : null,
            'transfer_to' => (!empty($this->transfer_to) && !empty($this->category) && $this->category == 4) ? (int) $this->transfer_to : null,
        ]);
    }
}
