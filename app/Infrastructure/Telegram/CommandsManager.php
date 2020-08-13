<?php

namespace App\Infrastructure\Telegram;

use BotMan\BotMan\BotMan;
use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Collection;

class CommandsManager
{
    private BotMan $botMan;
    private Collection $commands;

    public function __construct(BotMan $botMan, array $commands)
    {
        $this->botMan = $botMan;
        $this->commands = collect($commands)
            ->map(function ($command) {
                return new $command($this->botMan);
            });
    }

    public function register(?Closure $filter = null, ?Closure $handler = null)
    {
        foreach ($this->commands as $command) {
            $this->botMan->hears($command->signature(), function ($bot, ...$args) use ($filter, $handler, $command) {
                if ($filter && !$filter($bot, $command)) {
                    $bot->reply('Access denied.');
                    return;
                }

                try {
                    if ($handler) {
                        $handler($command, ...$args);
                    } else {
                        $command->handle(...$args);
                    }
                } catch (AuthorizationException $e) {
                    $bot->reply($e->getMessage());
                }
            });
        }

        $this->registerHelp($filter);
    }

    protected function registerHelp(?Closure $filter = null): void
    {
        $this->botMan->hears('/help', function ($bot) use ($filter) {
            $text = "/help - available commands\n";
            foreach ($this->commands as $command) {
                if (!$filter || $filter($bot, $command)) {
                    $text .= $command->signature() . ' - ' . $command->description() . "\n";
                }
            }

            if (!empty($text)) {
                $this->botMan->reply($text);
            } else {
                $this->botMan->reply('Commands not found.');
            }
        });
    }
}
