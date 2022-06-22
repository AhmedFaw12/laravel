<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class AhmedRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    //check on value ,
    public function passes($attribute, $value)
    {
        return $value == "ahmed";//if value == ahmed it will pass , no error appear
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute is Invalid.';
    }
}
