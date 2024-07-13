<?php

namespace Ferdiunal\NovaPasswordConfirmModal\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PasswordVerifyRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! \Hash::check($value, \Auth::user()->password)) {
            $fail('The :attribute is incorrect.');
        }
    }
}
