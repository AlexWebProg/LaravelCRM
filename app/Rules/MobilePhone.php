<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;

class MobilePhone implements InvokableRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
        if (strlen($value) !== 11 || $value[0] !== '7') {
            $fail('Номер телефона должен содержать 11 цифр и начинаться с +7 или 8');
        }
    }
}
