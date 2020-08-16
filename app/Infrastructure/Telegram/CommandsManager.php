<?php

namespace App\Infrastructure\Telegram;

use BotMan\BotMan\BotMan;
use Closure;
use Exception;
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

    /**
     * Регистрация коммнад
     * @param Closure|null $filter
     * @param Closure|null $handler
     */
    public function register(?Closure $filter = null, ?Closure $handler = null)
    {
        foreach ($this->commands as $command) {
            $this->botMan->hears($command->pattern(), function ($bot, ...$args) use ($filter, $handler, $command) {
                if ($filter && !$filter($bot, $command)) {
                    throw new AuthorizationException();
                }

                if ($handler) {
                    $handler($command, $command->args());
                } else {
                    $command->handle($command->args());
                }
            });
        }

        $this->botMan->exception(Exception::class, function ($e, BotMan $bot) {
            if ($e instanceof AuthorizationException) {
                $bot->reply($e->getMessage());
            } else {
                $bot->reply(
                    config('app.debug') ? $e->getMessage() : 'Sorry, something went wrong'
                );
            }
        });

        $this->registerHelp($filter);

        $this->botMan->fallback(function ($bot) {
            $bot->reply('Sorry, I did not understand these commands. Use /help command to get a list of available commands.');
        });
    }

    protected function registerHelp(?Closure $filter = null): void
    {
        $this->botMan->hears('/help', function ($bot) use ($filter) {
            $text = "/help - List of available commands\n";

            $groupedCommands = $this->commands->groupBy(function ($command) {
                return $command->forManager() ? "Manager commands" : "User commands";
            });

            foreach ($groupedCommands as $group => $commands) {
                $text .= "\n*{$group}*\n---\n";
                foreach ($commands as $command) {
                    if (!$filter || $filter($bot, $command)) {
                        $text .= $command->help();
                    }
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
