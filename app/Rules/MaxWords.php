<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class MaxWords implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $word_count = str_word_count($value);
        if($word_count > 100) {
            $fail('Maksimum 100 kata, tidak boleh lebih dari 100 kata');
        }
    }
}
