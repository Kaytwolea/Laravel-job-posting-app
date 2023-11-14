<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Str;

class Phone implements Rule
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
    public function passes($attribute, $value)
    {
        $first_3_xters = Str::of($value)->substr(0, 4);
        $pattern = '/^0(?:70[1-68]|8(?:0[235-9]|1[0-8])|9(?:0[1-9]|1[2356]))$/';
        $check = preg_match($pattern, $first_3_xters, $matches);

        if ($check && Str::length($value) === 11) {
            return true;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be a valid phone number';
    }
}
