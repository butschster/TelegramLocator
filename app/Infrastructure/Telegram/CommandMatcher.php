<?php

namespace App\Infrastructure\Telegram;

use BotMan\BotMan\Interfaces\Middleware\Matching;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use BotMan\BotMan\Messages\Matcher;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CommandMatcher extends Matcher
{
    /**
     * @param IncomingMessage $message
     * @param Answer $answer
     * @param string $pattern
     * @param Matching[] $middleware
     * @return int
     */
    public function isPatternValid(IncomingMessage $message, Answer $answer, $pattern, $middleware = [])
    {
        $matched = false;
        $this->matches = [];

        if ($this->isCommand($pattern) && $pattern === $this->getIncomingMessageCommand($message)) {
            $matched = true;
        } else {
            $answerText = $answer->getValue();
            if (is_array($answerText)) {
                $answerText = '';
            }

            $pattern = str_replace('/', '\/', $pattern);
            $text = '/^' . preg_replace(self::PARAM_NAME_REGEX, '(?<$1>.*)', $pattern) . ' ?$/miu';

            $matched = (bool) preg_match($text, $message->getText(), $this->matches)
                || (bool) preg_match($text, $answerText, $this->matches);
        }

        // Try middleware first
        if (count($middleware)) {
            return Collection::make($middleware)
                    ->reject(function (Matching $middleware) use ($message, $pattern, $matched) {
                        return $middleware->matching($message, $pattern, $matched);
                    })->isEmpty() === true;
        }

        return $matched;
    }

    /**
     * @param string $pattern
     * @return bool
     */
    protected function isCommand(string $pattern): bool
    {
        return Str::startsWith($pattern, '/');
    }

    protected function getIncomingMessageCommand(IncomingMessage $message): ?string
    {
        $entities = $message->getPayload()->get('entities');
        if (!is_array($entities)) {
            return null;
        }

        foreach ($entities as $entity) {
            if (isset($entity['type']) && $entity['type'] == 'bot_command') {
                return substr($message->getText(), $entity['offset'], $entity['length']);
            }
        }

        return null;
    }
}
