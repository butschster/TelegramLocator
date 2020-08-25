<?php

namespace App\Infrastructure\Telegram\Contracts;

use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;

interface Matcher
{
    public function validate(IncomingMessage $message, Answer $answer, string $pattern): bool;
}
