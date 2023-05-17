<?php
namespace App\Http\Requests\Manager\ExpensesObject;

trait CommonStoreUpdateData
{

    protected function commonRules()
    {
        return [
            'date' => 'nullable|date',
            'сhk_and_del_det' => 'nullable|string',
            'chk_amount_string' => 'nullable|string',
            'chk_amount' => 'nullable|numeric',
            'garb_amount_string' => 'nullable|string',
            'garb_amount' => 'nullable|numeric',
            'tool_comment' => 'nullable|string',
            'tool_amount_string' => 'nullable|string',
            'tool_amount' => 'nullable|numeric',
            'work_pay' => 'nullable|string',
            'received_sum_string' => 'nullable|string',
            'received_sum' => 'nullable|numeric',
            'client_id' => 'nullable|integer',
        ];
    }

    protected function commonMessages()
    {
        return [
            'date.date' => 'Это поле должно быть датой в формате дд.мм.гггг',
            'сhk_and_del_det.string' => 'Это поле должно быть текстом',
            'chk_amount_string.string' => 'Это поле должно быть указано числом в формате 0.00',
            'chk_amount.numeric' => 'Это поле должно быть указано числом в формате 0.00',
            'garb_amount_string.string' => 'Это поле должно быть указано числом в формате 0.00',
            'garb_amount.numeric' => 'Это поле должно быть указано числом в формате 0.00',
            'tool_comment.string' => 'Это поле должно быть текстом',
            'tool_amount_string.string' => 'Это поле должно быть указано числом в формате 0.00',
            'tool_amount.numeric' => 'Это поле должно быть указано числом в формате 0.00',
            'work_pay.string' => 'Это поле должно быть текстом',
            'received_sum_string.string' => 'Это поле должно быть указано числом в формате 0.00',
            'received_sum.numeric' => 'Это поле должно быть указано числом в формате 0.00',
            'client_id.integer' => 'Произошла неизвестная ошибка. Попробуйте обновить страницу',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'date' => !empty($this->date) ? date('Y-m-d',strtotime($this->date)) : null,
            'chk_amount' => !empty($this->chk_amount) ? (int) (floatval(str_replace([' ',','], ['','.'], $this->chk_amount)) * 100) : null,
            'garb_amount' => !empty($this->garb_amount) ? (int) (floatval(str_replace([' ',','], ['','.'], $this->garb_amount)) * 100) : null,
            'tool_amount' => !empty($this->tool_amount) ? (int) (floatval(str_replace([' ',','], ['','.'], $this->tool_amount)) * 100) : null,
            'received_sum' => !empty($this->received_sum) ? (int) (floatval(str_replace([' ',','], ['','.'], $this->received_sum)) * 100) : null,
        ]);
    }
}
