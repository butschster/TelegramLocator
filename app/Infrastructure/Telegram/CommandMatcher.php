<?php

namespace App\Infrastructure\Telegram;

use BotMan\BotMan\Interfaces\Middleware\Matching;
use BotMan\BotMan\Messages\Attachments\Location;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use BotMan\BotMan\Messages\Matcher;
use Illuminate\Auth\Access\AuthorizationException;
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
     * @throws AuthorizationException
     */
    public function isPatternValid(IncomingMessage $message, Answer $answer, $pattern, $middleware = [])
    {
        $matched = false;
        $this->matches = [];

        $logger = logger();

        $logger->debug('Pattern validation', [
            'pattern' => $pattern,
            'text' => $message->getText()
        ]);

        // Проверяем на то, что пришла команда от пользователя
        if ($this->isCommand($pattern) && $pattern === $this->getIncomingMessageCommand($message)) {
            $matched = true;

            $logger->debug('Pattern validated', [
                'type' => 'command'
            ]);
            // Проверяем что пришла информация о местоположении и оно текущее
        } else if ($this->isLocation($pattern) && $pattern === $message->getText() && $this->isCurrentLocation($message)) {
            $matched = true;

            $logger->debug('Pattern validated', [
                'type' => 'location'
            ]);

            // Провреяем остальные варианты
        } else {
            $answerText = $answer->getValue();
            if (is_array($answerText)) {
                $answerText = '';
            }

            $pattern = str_replace('/', '\/', $pattern);
            $text = '/^' . preg_replace(self::PARAM_NAME_REGEX, '(?<$1>.*)', $pattern) . ' ?$/miu';

            $matched = (bool)preg_match($text, $message->getText(), $this->matches)
                || (bool)preg_match($text, $answerText, $this->matches);


            if ($matched) {
                $logger->debug('Pattern validated', [
                    'type' => 'text'
                ]);
            }
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
