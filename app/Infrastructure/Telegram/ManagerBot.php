<?php

namespace App\Infrastructure\Telegram;

use BotMan\BotMan\BotMan;

class ManagerBot implements Contracts\Bot
{
    private BotMan $botMan;
    private CommandsManager $commands;

    public function __construct(BotMan $botMan, CommandsManager $commands)
    {
        $this->botMan = $botMan;
        $this->commands = $commands;
    }

    public function handleCommand(): void
    {
        $this->commands->register();

        $this->botMan->listen();
    }
}
