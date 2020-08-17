<?php

namespace App\Infrastructure\Telegram;

use App\Infrastructure\Telegram\Contracts\Api as ApiContract;
use App\Infrastructure\Telegram\Contracts\Command;
use App\Models\Room;
use App\Telegram\Room\StoreLocation;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Attachments\Location;

class RoomBot implements Contracts\Bot
{
    private BotMan $botMan;
    private Room $room;
    private CommandsManager $commands;
    private ApiContract $api;

    public function __construct(BotMan $botMan, ApiContract $api, Room $room, CommandsManager $commands)
    {
        $this->botMan = $botMan;
        $this->room = $room;
        $this->commands = $commands;
        $this->api = $api;
    }

    public function handleCommand(): void
    {
        $this->commands->register(function (BotMan $botMan, Command $command) {
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

        // Добавляем команду для отправки текущей позиции пользователя
        $this->botMan->receivesLocation(function($bot, Location $location) {
            $command = new StoreLocation($this->botMan, $this->api);

            $args = $command->args();
            $args->setArgument('room', $this->room);
            $args->setArgument('location', $location);

            $command->handle($args);
        });

        $this->botMan->listen();
    }
}
