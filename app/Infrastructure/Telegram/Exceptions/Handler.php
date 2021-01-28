<?php

namespace App\Infrastructure\Telegram\Exceptions;

use BotMan\BotMan\BotMan;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler
{
    private BotMan $bot;

    public function __construct(BotMan $bot)
    {
        $this->bot = $bot;
    }

    public function renderForTelegram(Throwable $e)
    {
        if ($e instanceof ValidationException) {
            return $this->showValidationError($e);
        } else if ($e instanceof NotEnoughArgumentsException) {
            return $this->showCommandException($e);
        } else if ($e instanceof AuthorizationException) {
            return $this->showAuthorizationError($e);
        }

        $this->showError($e);
    }

    private function showAuthorizationError(AuthorizationException $e): void
    {
        $this->send($e->getMessage());
    }

    private function showError(Throwable $e)
    {
        report($e);

        $this->send(
            config('app.debug')
                ? $e->getMessage()
                : trans('app.command.error')
        );
    }

    private function showValidationError(ValidationException $e): void
    {
        $errors = collect($e->errors())->map(function (array $errors, $field) {
            $message = "{$field}\n";
            foreach ($errors as $error) {
                $message .= " - {$error}\n";
            }

            return $message;
        })->implode("\n");

        $this->send(
            sprintf("*%s*\n```\n%s\n```", trans('app.command.invalid_data'), $errors)
        );
    }

    private function showCommandException(NotEnoughArgumentsException $e): void
    {
        $arguments = collect($e->getArguments())->map(function (string $arg) {
            return ' - ' . $arg;
        })->implode("\n");

        $this->send(
            sprintf("*%s*\n```\n%s\n```", trans('app.command.not_enough_arguments'), $arguments)
        );
    }

    private function send(string $message): void
    {
        $this->bot->replyAll($message);
    }
}
