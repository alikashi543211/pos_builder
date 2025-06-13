<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class XssFree implements Rule
{
    protected $forbiddenTags = ['script', 'iframe', 'object']; // Add more tags as needed
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $value = html_entity_decode($value);
        foreach ($this->forbiddenTags as $tag) {
            if (stripos($value, "<" . $tag) !== false) {
                return false;
            }
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute contains forbidden HTML tags.';
    }

}
