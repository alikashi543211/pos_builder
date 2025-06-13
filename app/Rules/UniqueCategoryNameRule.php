<?php

namespace App\Rules;

use App\Models\CategoriesLangModel;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class UniqueCategoryNameRule implements Rule
{
    protected $language_id, $category_id;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($language_id, $category_id = null)
    {
        $this->category_id = $category_id;
        $this->language_id = $language_id;
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
        // Check if the email already exists in the users table
        $query = CategoriesLangModel::whereLangId($this->language_id)
            ->where('deleted_at', NULL)
            ->whereCategoryName($value);
        if($this->category_id)
        {
            $query->where('category_id', '!=', $this->category_id);
        }

        return $query->doesntExist();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute has already been taken.';
    }
}
