<?php

namespace App\Infrastructure\Telegram;

use App\Infrastructure\Telegram\Contracts\Api as ApiContract;
use App\Infrastructure\Telegram\Contracts\Command;
use App\Models\Room;
use BotMan\BotMan\BotMan;

class RoomBot implements Contracts\Bot
{
    private BotMan $botMan;
    private Room $room;
    private CommandsManager $commands;
    private ApiContract $api;
    private MiddlewareManager $middleware;

    public function __construct(BotMan $botMan, ApiContract $api, Room $room, CommandsManager $commands, MiddlewareManager $middleware)
    {
        $this->botMan = $botMan;
        $this->room = $room;
        $this->commands = $commands;
        $this->api = $api;
        $this->middleware = $middleware;
    }

    public function handleCommand(): void
    {
        $this->middleware->register($this->botMan);
        $this->commands->listen(function (BotMan $botMan, Command $command) {

            // Если команды имеют флаг "Только для менеджера", то обычный
            // пользователь не может их видеть и выполнять
            return !$command->forManager()
                || (
                    $command->forManager()
                    && $this->room->isOwner($command->getUser())
                );

        }, function (Command $command, StringInput $args) {

            // Добавляем в аргументы объект комнаты
            $args->setArgument('room', $this->room);
            $command->handle($args);

        });
    }
}
