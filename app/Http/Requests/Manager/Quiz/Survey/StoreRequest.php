<?php

namespace App\Http\Requests\Manager\Quiz\Survey;

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
                        'rating_from' => empty($this->q_rating_from[$k]) ? null : $this->q_rating_from[$k],
                        'rating_to' => empty($this->q_rating_to[$k]) ? null : $this->q_rating_to[$k],
                        'comment_enabled' => empty($this->q_comment_enabled[$k]) ? null : $this->q_comment_enabled[$k],
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
            'template_id' => 'required|integer|exists:quiz_template,id,deleted_at,NULL',
            'all_clients' => 'nullable|in:1',
            'to_in_process_1' => 'nullable|in:1',
            'to_in_process_0' => 'nullable|in:1',
            'to_ob_status_2' => 'nullable|in:1',
            'client' => 'required|array',
            'client.*' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'template_id.required' => 'Пожалуйста, выберите шаблон опроса',
            'template_id.integer' => 'Пожалуйста, выберите шаблон опроса',
            'template_id.exists' => 'Пожалуйста, выберите шаблон опроса',
            'client.required' => 'Пожалуйста, выберите хотя бы один объект',
            'client.*.required' => 'Пожалуйста, выберите хотя бы один объект',
            'client.*.integer' => 'Пожалуйста, выберите хотя бы один объект',
        ];
    }
}
