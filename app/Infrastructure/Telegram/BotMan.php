<?php

namespace App\Infrastructure\Telegram;

use BotMan\BotMan\BotMan as BaseBotMan;
use BotMan\BotMan\Interfaces\CacheInterface;
use BotMan\BotMan\Interfaces\DriverInterface;
use BotMan\BotMan\Interfaces\StorageInterface;
use BotMan\BotMan\Messages\Matcher;

class BotMan extends BaseBotMan
{
    public function __construct(
        CacheInterface $cache,
        DriverInterface $driver,
        $config,
        StorageInterface $storage,
        ?Matcher $matcher = null
    ) {
        parent::__construct($cache, $driver, $config, $storage);
        $this->conversationManager = new ConversationManager(
            $matcher ?: new Matcher()
        );
    }
}
