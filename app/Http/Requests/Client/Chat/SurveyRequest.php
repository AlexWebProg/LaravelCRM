<?php

namespace App\Http\Requests\Client\Chat;

use Illuminate\Foundation\Http\FormRequest;

class SurveyRequest extends FormRequest
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
        $arAnswers = [];
        if (!empty($this->question_id) && count($this->question_id)) {
            foreach ($this->question_id as $question_id) {
                $arAnswers[$question_id] =
                    [
                        'survey_id' => empty($this->survey_id) ? null : $this->survey_id,
                        'question_id' => $question_id,
                        'rating_required' => empty($this->rating_required[$question_id]) ? null : 1,
                        'rating' => empty($this->rating[$question_id]) ? null : $this->rating[$question_id],
                        'comment' => empty($this->comment[$question_id]) ? null : $this->comment[$question_id],
                        'comment_required' => empty($this->comment_required[$question_id]) ? null : 1,
                    ];
            }
        }
        $this->merge(['answers' => $arAnswers]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'message_id' => 'required|integer',
            'survey_id' => 'required|integer',
            'answers.*.survey_id' => 'required|integer',
            'answers.*.question_id' => 'required|integer',
            'answers.*.rating' => 'nullable|integer|required_if:answers.*.rating_required,1',
            'answers.*.comment' => 'nullable|string|required_if:answers.*.comment_required,1',
        ];
    }

    public function messages()
    {
        return [

        ];
    }
}
