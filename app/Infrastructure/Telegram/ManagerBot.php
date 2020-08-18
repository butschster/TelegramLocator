<?php

namespace App\Infrastructure\Telegram;

use BotMan\BotMan\BotMan;

class ManagerBot implements Contracts\Bot
{
    private BotMan $botMan;
    private CommandsManager $commands;
    private MiddlewareManager $middleware;

    public function __construct(BotMan $botMan, CommandsManager $commands, MiddlewareManager $middleware)
    {
        $this->botMan = $botMan;
        $this->commands = $commands;
        $this->middleware = $middleware;
    }

    public function handleCommand(): void
    {
        $this->middleware->register($this->botMan);
        $this->commands->listen();
    }
}
