<?php

namespace App\Infrastructure\Telegram;

use App\Infrastructure\Telegram\Contracts\Api as ApiContract;
use BotMan\BotMan\BotMan;
use Closure;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class CommandsManager
{
    private BotMan $botMan;
    /**
     * @var Collection|\Tightenco\Collect\Support\Collection|Command[]
     */
    private Collection $commands;
    private ApiContract $api;

    public function __construct(BotMan $botMan, ApiContract $api, array $commands)
    {
        $this->botMan = $botMan;
        $this->api = $api;
        $this->commands = collect($commands)->map(function ($command) use($api) {
            return new $command($this->botMan, $api);
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
            $this->botMan->hears($command->name(), function ($bot, ...$args) use ($filter, $handler, $command) {
                if ($filter && !$filter($bot, $command)) {
                    throw new AuthorizationException();
                }

                $args = $command->args();


                if ($handler) {
                    $handler($command, $args);
                } else {
                    $command->handle($args);
                }
            });
        }

        $this->botMan->exception(Exception::class, function ($e, BotMan $bot) {
            if ($e instanceof AuthorizationException) {
                $bot->reply($e->getMessage());
            } else if($e instanceof ValidationException) {
                $errors = collect($e->errors())->map(function (array $errors, $field) {
                    $message = "{$field}\n";
                    foreach ($errors as $error) {
                        $message .= " - {$error}\n";
                    }

                    return $message;
                })->implode("\n");
                $bot->reply(sprintf("*Data is not valid*\n```\n%s\n```", $errors));
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
            $text = "";

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
