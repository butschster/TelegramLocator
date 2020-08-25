<?php

namespace App\Infrastructure\Telegram;

use BotMan\BotMan\Interfaces\Middleware\Matching;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use BotMan\BotMan\Messages\Matcher;
use Illuminate\Support\Collection;

class CommandMatcher extends Matcher
{
    private Collection $matchers;

    public function __construct(array $matchers = [])
    {
        $this->matchers = Collection::make($matchers);
        $this->matches = [];
    }

    /**
     * @param IncomingMessage $message
     * @param Answer $answer
     * @param string $pattern
     * @param array $middleware
     * @return bool|int
     */
    public function isPatternValid(IncomingMessage $message, Answer $answer, $pattern, $middleware = [])
    {
        $matched = $this->matchers->first(function (Contracts\Matcher $matcher) use ($message, $answer, $pattern) {
            return $matcher->validate($message, $answer, $pattern);
        }) !== null;

        // Try middleware first
        if (count($middleware)) {
            return Collection::make($middleware)
                    ->reject(function (Matching $middleware) use ($message, $pattern, $matched) {
                        return $middleware->matching($message, $pattern, $matched);
                    })->isEmpty() === true;
        }

        return $matched;
    }
}
