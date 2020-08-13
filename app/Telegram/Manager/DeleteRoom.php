<?php

namespace App\Telegram\Manager;

use App\Infrastructure\Telegram\ManagerCommand;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DeleteRoom extends ManagerCommand
{
    public function signature(): string
    {
        return '/delete_room {token}';
    }

    public function description(): string
    {
        return 'Delete room';
    }

    public function handle(string $token)
    {
        $user = $this->getManager();

        try {
            $room = $user->rooms()->where('telegram_token', $token)->firstOrFail();
            $room->delete();
            $this->bot->reply('Room deleted.');
        } catch (ModelNotFoundException $e) {
            $this->bot->reply('Room not found.');
        }
    }
}
