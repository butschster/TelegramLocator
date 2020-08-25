<?php

namespace App\Telegram\Matchers;

use App\Infrastructure\Telegram\Contracts\Matcher;
use BotMan\BotMan\Messages\Attachments\Location;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use Illuminate\Auth\Access\AuthorizationException;

class LocationCommand implements Matcher
{
    public function validate(IncomingMessage $message, Answer $answer, string $pattern): bool
    {
        return $this->isLocation($pattern)
            && $pattern === $message->getText()
            && $this->isCurrentLocation($message);
    }

    /**
     * @param string $pattern
     * @return bool
     */
    protected function isLocation(string $pattern): bool
    {
        return $pattern === Location::PATTERN;
    }

    protected function isCurrentLocation(IncomingMessage $message): bool
    {
        if ($message->getPayload()->has('venue')) {
            throw new AuthorizationException(
                trans('app.command.user.only_current_location')
            );
        }

        return true;
    }
}
