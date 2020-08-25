<?php

namespace App\Telegram\Matchers;

use App\Infrastructure\Telegram\Contracts\Matcher;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;

class Conversation implements Matcher
{
    /**
     * regular expression to capture named parameters but not quantifiers
     * captures {name}, but not {1}, {1,}, or {1,2}.
     */
    const PARAM_NAME_REGEX = '/\{((?:(?!\d+,?\d+?)\w)+?)\}/';

    public function validate(IncomingMessage $message, Answer $answer, string $pattern): bool
    {
        $answerText = $answer->getValue();
        if (is_array($answerText)) {
            $answerText = '';
        }

        $pattern = str_replace('/', '\/', $pattern);
        $text = '/^' . preg_replace(self::PARAM_NAME_REGEX, '(?<$1>.*)', $pattern) . ' ?$/miu';

        return (bool)preg_match($text, $message->getText(), $matches)
            || (bool)preg_match($text, $answerText, $matches);
    }
}
