<?php

namespace App\Infrastructure\Telegram;

use App\Infrastructure\Telegram\Contracts\Api as ApiContract;
use App\Infrastructure\Telegram\Exceptions\NotEnoughArgumentsException;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Attachments\Attachment;
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
        $this->commands = collect($commands)->map(function ($command) use ($api) {
            return new $command($this->botMan, $api);
        });
    }

    /**
     * @param Closure|null $filter
     * @param Closure|null $handler
     */
    public function listen(?Closure $filter = null, ?Closure $handler = null): void
    {
        $this->registerExceptionsHandler();
        $this->register($filter, $handler);
        $this->botMan->listen();
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

                $commandArgs = $command->args();
                foreach ($args as $arg) {
                    if ($arg instanceof Attachment) {
                        $key = strtolower(class_basename($arg));
                        $commandArgs->setArgument($key, $arg);
                    }
                }

                if ($handler) {
                    $handler($command, $commandArgs);
                } else {
                    $command->handle($commandArgs);
                }
            });
        }

        $this->registerHelp($filter);

        $this->botMan->fallback(function ($bot) {
            $bot->reply(trans('app.command.fallback'));
        });
    }

    protected function registerHelp(?Closure $filter = null): void
    {
        // Список команд со списком аргументов для всех
        $this->botMan->hears('/help', function ($bot) use ($filter) {
            $text = "";

            $groupedCommands = $this->commands->groupBy(function ($command) {
                return $command->forManager()
                    ? trans('app.command.for_manager')
                    : trans('app.command.for_user');
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
                $this->botMan->reply(trans('app.command.empty_list_of_commands'));
            }
        });


        // Список команд в формате для добавления в настройках бота
        $this->botMan->hears('/commands', function ($bot) use ($filter) {
            $text = sprintf("help - %s\n", trans('app.command.help.description'));

            foreach ($this->commands as $command) {
                if (!$filter || $filter($bot, $command)) {
                    if ($command instanceof LocationCommand) {
                        continue;
                    }

                    $text .= ltrim($command->name(), '/') . ' - ' . $command->description() . "\n";
                }
            }

            $this->botMan->reply($text);
        });
    }

    protected function registerExceptionsHandler()
    {
        $this->botMan->exception(ValidationException::class, function ($e, BotMan $bot) {
            $errors = collect($e->errors())->map(function (array $errors, $field) {
                $message = "{$field}\n";
                foreach ($errors as $error) {
                    $message .= " - {$error}\n";
                }

                return $message;
            })->implode("\n");

            $bot->reply(sprintf("*%s*\n```\n%s\n```", trans('app.command.invalid_data'), $errors));
        });

        $this->botMan->exception(NotEnoughArgumentsException::class, function ($e, BotMan $bot) {
            $arguments = collect($e->getArguments())->map(function (string $arg) {
                return ' - ' . $arg;
            })->implode("\n");

            $bot->replyAll(sprintf("*%s*\n```\n%s\n```", trans('app.command.not_enough_arguments'), $arguments));
        });

        $this->botMan->exception(AuthorizationException::class, function ($e, BotMan $bot) {
            $bot->replyAll($e->getMessage());
        });

        $this->botMan->exception(AuthorizationException::class, function ($e, BotMan $bot) {
            $bot->replyAll($e->getMessage());
        });

        $this->botMan->exception(Exception::class, function (Exception $e, BotMan $bot) {
            $bot->replyAll(
                config('app.debug')
                    ? $e->getMessage()
                    : trans('app.command.error')
            );
        });
    }
}
