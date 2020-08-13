<?php

namespace App\Infrastructure\Telegram;

use App\Infrastructure\Telegram\Contracts\Command;
use App\Models\Room;
use App\Telegram\Room\StoreLocation;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Attachments\Location;

class RoomBot implements Contracts\RoomBot
{
    private BotMan $botMan;
    private Room $room;
    private CommandsManager $commands;

    public function __construct(BotMan $botMan, Room $room, CommandsManager $commands)
    {
        $this->botMan = $botMan;
        $this->room = $room;
        $this->commands = $commands;
    }

    public function handleCommand(): void
    {
        $this->commands->register(function (BotMan $botMan, Command $command) {
            return !$command->forManager()
                || (
                    $command->forManager()
                    && $command->getUserHash() === $this->room->user_id
                );
        }, function (Command $command, ...$args) {
            $command->handle($this->room, ...$args);
        });

        $this->botMan->receivesLocation(function($bot, Location $location) {
            $command = new StoreLocation($this->botMan);
            $command->handle($this->room, $location);
        });

        $this->botMan->listen();
    }
}
