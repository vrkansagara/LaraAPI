<?php

namespace App\Rules\Password;

use Illuminate\Contracts\Validation\Rule;

class ExtraStrongPassword implements Rule
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
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        /**
         * ^                         Start anchor
         * (?=.*[A-Z].*[A-Z])        Ensure string has two uppercase letters.
         * (?=.*[!@#$&*])            Ensure string has one special case letter.
         * (?=.*[0-9].*[0-9])        Ensure string has two digits.
         * (?=.*[a-z].*[a-z].*[a-z]) Ensure string has three lowercase letters.
         * .{8}                      Ensure string is of length 8.
         * $                         End anchor.
         */
        $pattern = '^(?=.*[A-Z].*[A-Z])(?=.*[!@#$&*])(?=.*[0-9].*[0-9])(?=.*[a-z].*[a-z].*[a-z]).{8}$';
        if (preg_match($pattern, $value)) {
            return true;
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be one lower case , one upper case, one special character and one number';
    }
}
