<?php

namespace App\Validation\Rules;

use Illuminate\Contracts\Validation\Rule;

class TelegramToken implements Rule
{
    /**
     * @inheritDoc
     */
    public function passes($attribute, $value)
    {
        return preg_match(
            "/[0-9]{9}:[a-zA-Z0-9_-]{35}/m",
            $value
        );
    }

    /**
     * @inheritDoc
     */
    public function message()
    {
        return trans('app.command.invalid_bot_token');
    }
}
