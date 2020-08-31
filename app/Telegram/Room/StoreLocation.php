<?php

namespace App\Telegram\Room;

use App\Infrastructure\Telegram\LocationCommand;
use App\Infrastructure\Telegram\StringInput;
use App\Jobs\Points\StorePoint;
use App\Models\Room;
use BotMan\BotMan\Messages\Attachments\Location;

class StoreLocation extends LocationCommand
{
    public function description(): string
    {
        return trans('app.command.store_user_location.description');
    }

    public function handle(StringInput $input): void
    {
        /** @var Room $room */
        $room = $input->getArgument('room');
        /** @var Location $location */
        $location = $input->getArgument('location');

        $this->checkAuthentication($room);

        $lock = $this->getUser()->getLock();
        if ($lock->get()) {
            dispatch(
                new StorePoint($room->uuid, $this->getUser(), $location)
            );
            $this->bot->reply(
                trans('app.command.store_user_location.stored', [
                    'lat' => $location->getLatitude(),
                    'lng' => $location->getLongitude()
                ])
            );
        } else {
            $this->bot->reply(
                trans('app.command.store_user_location.slow_down')
            );
        }
    }
}
