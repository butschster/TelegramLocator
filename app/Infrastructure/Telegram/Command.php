<?php

namespace App\Infrastructure\Telegram;

use App\Infrastructure\Telegram\User as TelegramUser;
use App\Models\Room;
use App\Models\User;
use BotMan\BotMan\BotMan;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Console\Parser;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;

abstract class Command implements Contracts\Command
{
    protected BotMan $bot;
    private InputDefinition $definition;
    protected ?string $name = null;
    private ?TelegramUser $user = null;
    protected Contracts\Api $api;
    protected bool $showHelp = true;

    public function __construct(BotMan $bot, Contracts\Api $api)
    {
        $this->bot = $bot;
        $this->api = $api;
        $this->definition = new InputDefinition();
        $this->configureUsingFluentDefinition();
    }

    /** @inheritDoc */
    public function group(): string
    {
        return trans('app.command.for_user');
    }

    /** @inheritDoc */
    public function isAllow(StringInput $input): bool
    {
        return true;
    }

    /**
     * Получение модели владельца канала
     * @return User
     * @throws AuthorizationException
     */
    public function getManager(): User
    {
        try {
            return User::findOrFail($this->getUser()->getHash());
        } catch (ModelNotFoundException $e) {
            throw new AuthorizationException(
                trans('app.command.manager.register_required')
            );
        }
    }

    /**
     * Получение объекта Telegram пользователя
     * @return \App\Infrastructure\Telegram\User
     */
    public function getUser(): TelegramUser
    {
        if ($this->user) {
            return $this->user;
        }

        return $this->user = new TelegramUser(
            $this->bot->getUser()->getId(),
            $this->bot->getUser()->getUsername()
        );
    }

    /**
     * Проверка доступа пользователя к каналу
     * @param Room $room
     * @throws AuthorizationException
     */
    public function checkAuthentication(Room $room): void
    {
        if (!$room->hasAccess($this->getUser())) {
            throw new AuthorizationException(
                trans('app.command.user.unauthorized')
            );
        }
    }

    /** @inheritDoc */
    public function name(): string
    {
        return $this->name;
    }

    /** @inheritDoc */
    public function pattern(): string
    {
        if ($this->definition->getArgumentCount() > 0) {
            $args = collect($this->definition->getArguments())->map(function ($arg) {
                return '{' . $arg->getName() . '}';
            })->implode(' ');

            return $this->name . ' ' . $args;
        }

        return $this->name;
    }

    /** @inheritDoc */
    public function help(): string
    {
        if (!$this->showHelp) {
            return '';
        }

        $command = "*{$this->description()}*\n```\n";
        $command .= sprintf(
            "%s %s\n",
            $this->name(),
            $this->definition->getSynopsis()
        );

        $args = [];
        foreach ($this->definition->getArguments() as $argument) {
            $description = $argument->getDescription();
            if (!empty($description)) {
                $args[] = sprintf("%s - %s", $argument->getName(), $description);
            }
        }

        if (!empty($args)) {
            $command .= implode("\n", $args) . "\n";
        }

        return $command . "```" . "\n";
    }

    /**
     * Configure the console command using a fluent definition.
     *
     * @return void
     */
    protected function configureUsingFluentDefinition(): void
    {
        [$name, $arguments, $options] = Parser::parse($this->signature());

        $this->name = $name;

        $this->definition->addArguments($arguments);
        $this->definition->addOptions($options);
    }

    public function args(): InputInterface
    {
        $args = new StringInput(
            $this->bot->getMessage()->getText(),
            $this->definition
        );

        $args->validateCommand($this);

        return $args;
    }

    public function argsRules(): array
    {
        return [];
    }
}
