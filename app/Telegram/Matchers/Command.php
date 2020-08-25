<?php

namespace App\Telegram\Matchers;

use App\Infrastructure\Telegram\Contracts\Matcher;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use Illuminate\Support\Str;

class Command implements Matcher
{
    // Проверяем на то, что пришла команда от пользователя
    public function validate(IncomingMessage $message, Answer $answer, string $pattern): bool
    {
        return $this->isCommand($pattern)
            && $pattern === $this->getIncomingMessageCommand($message);
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
