<?php

namespace App\Infrastructure\Telegram;

use BotMan\BotMan\Commands\ConversationManager as BaseConversationManager;
use BotMan\BotMan\Interfaces\DriverInterface;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use BotMan\BotMan\Messages\Matcher;
use BotMan\BotMan\Messages\Matching\MatchingMessage;
use BotMan\BotMan\Middleware\MiddlewareManager;
use Illuminate\Support\Collection;

class ConversationManager extends BaseConversationManager
{
    private Matcher $matcher;

    public function __construct(Matcher $matcher)
    {
        $this->matcher = $matcher;
    }

    public function getMatchingMessages($messages, MiddlewareManager $middleware, Answer $answer, DriverInterface $driver, $withReceivedMiddleware = true): array
    {
        $messages = Collection::make($messages)->reject(function (IncomingMessage $message) {
            return $message->isFromBot();
        });

        $matchingMessages = [];
        foreach ($messages as $message) {
            if ($withReceivedMiddleware) {
                $message = $middleware->applyMiddleware('received', $message);
            }

            foreach ($this->listenTo as $command) {
                if ($this->matcher->isMessageMatching($message, $answer, $command, $driver, $middleware->matching())) {
                    $matchingMessages[] = new MatchingMessage($command, $message, $this->matcher->getMatches());
                }
            }
        }

        return $matchingMessages;
    }

}
