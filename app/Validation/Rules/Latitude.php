<?php

namespace App\Validation\Rules;

use Illuminate\Contracts\Validation\Rule;

class Latitude implements Rule
{
    /**
     * @inheritDoc
     */
    public function passes($attribute, $value)
    {
        return preg_match(
            "/^(-)?(?:90(?:(?:\.0{1,20})?)|(?:[0-9]|[1-8][0-9])(?:(?:\.[0-9]{1,20})?))$/",
            $value
        );
    }

    /**
     * @inheritDoc
     */
    public function message()
    {
        return 'The :attribute must be a valid latitude, with a limit of 20 digits after a decimal point';
    }
}