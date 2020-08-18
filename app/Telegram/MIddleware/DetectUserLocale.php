<?php

namespace App\Telegram\MIddleware;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\Interfaces\Middleware\Received;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;

class DetectUserLocale implements Received
{
    /**
     * Handle an incoming message.
     *
     * @param IncomingMessage $message
     * @param callable $next
     * @param BotMan $bot
     *
     * @return mixed
     */
    public function received(IncomingMessage $message, $next, BotMan $bot)
    {
        if ($message->getPayload() && $from = (array)$message->getPayload()->get('from')) {
            $locale = $from['language_code'] ?? 'en';
            trans()->setLocale($locale);
        }

        return $next($message);
    }
}
