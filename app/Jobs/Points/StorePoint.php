<?php

namespace App\Jobs\Points;

use App\Infrastructure\Telegram\User;
use App\Models\Room;
use BotMan\BotMan\Messages\Attachments\Location;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class StorePoint implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $roomUuid;
    public User $user;
    public Location $location;
    public int $retryAfter = 3;

    public function __construct(string $roomUuid, User $user, Location $location)
    {
        $this->roomUuid = $roomUuid;
        $this->user = $user;
        $this->location = $location;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $room = Room::findOrFail($this->roomUuid);

        Room\Point::storeForRoom(
            $room,
            $this->user,
            $this->location
        );
    }

    /**
     * Handle a job failure.
     *
     * @param  Exception  $exception
     * @return void
     */
    public function failed(Exception $exception)
    {
        $this->user->getLock()->release();
    }
}
