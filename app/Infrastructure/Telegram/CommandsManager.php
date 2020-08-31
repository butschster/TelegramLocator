<?php

namespace App\Infrastructure\Telegram;

use App\Infrastructure\Telegram\Contracts\Api as ApiContract;
use App\Infrastructure\Telegram\Exceptions\Handler;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Attachments\Attachment;
use Closure;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Collection;

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
     * @param Closure|null $handler
     */
    public function listen(?Closure $handler = null): void
    {
        $this->registerExceptionsHandler();
        $this->register($handler);
        $this->botMan->listen();
    }

    /**
     * Регистрация коммнад
     * @param Closure|null $handler
     */
    public function register(?Closure $handler = null)
    {
        foreach ($this->commands as $command) {
            $this->botMan->hears($command->name(), function ($bot, ...$args) use ($handler, $command) {
                $commandArgs = $command->args();
                $commandArgs->setArgument('commands', $this->commands);
                foreach ($args as $arg) {
                    if ($arg instanceof Attachment) {
                        $key = strtolower(class_basename($arg));
                        $commandArgs->setArgument($key, $arg);
                    }
                }

                if ($handler) {
                    $handler($command, $commandArgs);
                } else {
                    if (!$command->isAllow($commandArgs)) {
                        throw new AuthorizationException();
                    }

                    $command->handle($commandArgs);
                }
            });
        }

        $this->botMan->fallback(function ($bot) {
            $bot->reply(trans('app.command.fallback'));
        });
    }

    protected function registerExceptionsHandler()
    {
        $this->botMan->exception(Exception::class, function (Exception $e, BotMan $bot) {
            $handler = new Handler($bot);
            $handler->renderForTelegram($e);
        });
    }
}
