<?php

namespace App\Telegram\Room;

use App\Infrastructure\Telegram\Command;
use App\Models\Room;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Cache;
use MStaack\LaravelPostgis\Geometries\Point;
use BotMan\BotMan\Messages\Attachments\Location;

class StoreLocation extends Command
{
    public function signature(): string
    {
        return '';
    }

    public function description(): string
    {
        return 'Store user location';
    }

    public function handle(Room $room, Location $location): void
    {
        $this->checkAuthentication($room);

        $hash = $this->getUserHash();

        $lock = Cache::lock('points:' . $hash, 30);
        if ($lock->get()) {
            $room->points()->updateOrCreate([
                'owner_hash' => $this->getUserHash(),
            ], [
                'location' => new Point(
                    $location->getLatitude(),
                    $location->getLongitude()
                ),
                'username' => $room->is_anonymous ? null : $this->bot->getUser()->getUsername()
            ]);

            $this->bot->reply('Your location is stored.');
        } else {
            $this->bot->reply('Slow down...');
        }
    }
}
