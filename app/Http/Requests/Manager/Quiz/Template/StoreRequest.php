<?php

namespace App\Http\Requests\Manager\Quiz\Template;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $arQuestions = [];
        if (!empty($this->q_id) && count($this->q_id)) {
            foreach ($this->q_id as $k => $v) {
                $arQuestions[] =
                    [
                        'id' => empty($v) ? null : $v,
                        'text' => empty($this->q_text[$k]) ? null : $this->q_text[$k],
                        'rating_enabled' => empty($this->q_rating_enabled[$k]) ? null : $this->q_rating_enabled[$k],
                        'rating_required' => (empty($this->q_rating_enabled[$k]) || empty($this->q_rating_required[$k])) ? null : $this->q_rating_required[$k],
                        'rating_from' => empty($this->q_rating_from[$k]) ? null : $this->q_rating_from[$k],
                        'rating_to' => empty($this->q_rating_to[$k]) ? null : $this->q_rating_to[$k],
                        'comment_enabled' => empty($this->q_comment_enabled[$k]) ? null : $this->q_comment_enabled[$k],
                        'comment_required' => (empty($this->q_comment_enabled[$k]) || empty($this->q_comment_required[$k])) ? null : $this->q_comment_required[$k],
                        'sort' => $k,
                        'template_id' => $this->id
                    ];
            }
        }
        $this->merge(['question' => $arQuestions]);
    }

    public function rules()
    {
        return [
            'id' => 'nullable|integer',
            'name' => 'required|string|unique:quiz_template,name,' .$this->id.',id,deleted_at,NULL',
            'intro' => 'nullable|string',
            'question.*.id' => 'nullable|integer',
            'question.*.text' => 'required|string',
            'question.*.rating_enabled' => 'nullable|in:1|required_if:question.*.comment_enabled,NULL',
            'question.*.rating_required' => 'nullable|in:1',
            'question.*.rating_from' => 'nullable|integer|required_if:question.*.rating_enabled,1|min:1|max:100',
            'question.*.rating_to' => 'nullable|integer|required_if:question.*.rating_enabled,1|min:1|max:100',
            'question.*.comment_enabled' => 'nullable|in:1|required_if:question.*.rating_enabled,NULL',
            'question.*.comment_required' => 'nullable|in:1',
            'question.*.sort' => 'nullable|integer',
            'question.*.template_id' => 'nullable|integer',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Название обязательно для заполнения',
            'name.string' => 'Название должно быть строкой',
            'name.unique' => 'Такое название уже используется у другого шаблона',
            'intro.string' => 'Вводная фраза должна быть строкой',
            'question.*.text.required' => 'Текст вопроса обязателен для заполнения',
            'question.*.rating_from.min' => 'Оценка может принимать значения от 0 до 100',
            'question.*.rating_from.max' => 'Оценка может принимать значения от 0 до 100',
            'question.*.rating_from.required_if' => 'Не заполнена оценка ОТ',
            'question.*.rating_to.required_if' => 'Не заполнена оценка ДО',
            'question.*.rating_to.min' => 'Оценка может принимать значения от 0 до 100',
            'question.*.rating_to.max' => 'Оценка может принимать значения от 0 до 100',
            'question.*.rating_enabled.required_if' => 'В качестве ответа на вопрос заказчик должен поставить оценку и/или написать комментарий',
            'question.*.comment_enabled.required_if' => 'В качестве ответа на вопрос заказчик должен поставить оценку и/или написать комментарий',
        ];
    }
}
