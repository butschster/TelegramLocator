<?php

namespace App\Infrastructure\Telegram;

use BotMan\BotMan\BotMan as BaseBotMan;
use BotMan\BotMan\Interfaces\CacheInterface;
use BotMan\BotMan\Interfaces\DriverInterface;
use BotMan\BotMan\Interfaces\StorageInterface;
use BotMan\BotMan\Messages\Matcher;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;

class BotMan extends BaseBotMan
{
    public function __construct(
        CacheInterface $cache,
        DriverInterface $driver,
        $config,
        StorageInterface $storage,
        ?Matcher $matcher = null
    )
    {
        parent::__construct($cache, $driver, $config, $storage);
        $this->conversationManager = new ConversationManager(
            $matcher ?: new Matcher()
        );
    }

    /**
     * Отправляем ответ всем отправителям.
     * Этот метод позволяет отправить сообщение в том, случае если команда еще не выбрана.
     * @param string $message
     * @param array $additionalParameters
     * @return mixed
     */
    public function replyAll(string $message, array $additionalParameters = []): void
    {
        foreach ($this->getMessages() as $msg) {
            $this->outgoingMessage = \is_string($message) ? OutgoingMessage::create($message) : $message;

            $this->sendPayload(
                $this->getDriver()->buildServicePayload(
                    $this->outgoingMessage, $msg, $additionalParameters
                )
            );
        }
    }
}
