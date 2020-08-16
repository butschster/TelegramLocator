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
    private TelegramUser $user;

    public function __construct(BotMan $bot)
    {
        $this->bot = $bot;
        $this->definition = new InputDefinition();
        $this->configureUsingFluentDefinition();
    }

    /** @inheritDoc */
    public function forManager(): bool
    {
        return false;
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
                'You should register an account. Use /register command.'
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
            throw new AuthorizationException('Unauthorized! Please use /room_auth for authentication.');
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
        $command = sprintf(
            "`%s %s` - %s\n",
            $this->name,
            $this->definition->getSynopsis(),
            $this->description()
        );

        $args = [];
        foreach ($this->definition->getArguments() as $argument) {
            $description = $argument->getDescription();
            if (!empty($description)) {
                $args[] = sprintf("%s - %s", $argument->getName(), $description);
            }
        }

        if (!empty($args)) {
            $command .= "```\n";
            $command .= "-\n";
            $command .= implode("\n", $args) . "\n";
            $command .= "```";
        }

        return $command . "-------\n";
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
        return new StringInput(
            $this->bot->getMessage()->getText(),
            $this->definition
        );
    }
}
