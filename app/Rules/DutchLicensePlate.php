<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ValidationRule;
use App\Utils\DutchLicensePlatePatterns;

class DutchLicensePlate implements ValidationRule
{
    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        $clean = strtoupper(str_replace([' ', '-'], '', $value));

        if (!preg_match(DutchLicensePlatePatterns::validationPatterns(), $clean)) {
            $fail('This is not a valid Dutch license plate.');
        }
    }
}
