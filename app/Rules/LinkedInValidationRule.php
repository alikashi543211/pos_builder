<?php
namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class LinkedInValidationRule implements Rule
{
    public function passes($attribute, $value)
    {
        return str_contains($value, 'linkedin.com');
    }

    public function message()
    {
        return 'The :attribute must be a valid LinkedIn profile or company URL.';
    }
}
