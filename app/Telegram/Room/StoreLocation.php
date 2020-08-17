<?php

namespace App\Telegram\Room;

use App\Infrastructure\Telegram\Command;
use App\Infrastructure\Telegram\StringInput;
use App\Jobs\Points\StorePoint;
use App\Models\Room;
use BotMan\BotMan\Messages\Attachments\Location;

class StoreLocation extends Command
{
    public function signature(): string
    {
        return '/location';
    }

    public function description(): string
    {
        return 'Store user location';
    }

    public function handle(StringInput $input): void
    {
        /** @var Room $room */
        $room = $input->getArgument('room');
        /** @var Location $location */
        $location = $input->getArgument('location');

        $this->checkAuthentication($room);

        $hash = $this->getUser()->getHash();

        $lock = $this->getUser()->getLock();
        if ($lock->get()) {
            dispatch(
                new StorePoint($room->uuid, $this->getUser(), $location)
            );
            $this->bot->reply('Your location is stored.');
        } else {
            $this->bot->reply('Slow down...');
        }
    }
}
