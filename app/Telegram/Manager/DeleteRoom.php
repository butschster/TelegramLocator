<?php

namespace App\Telegram\Manager;

use App\Infrastructure\Telegram\ManagerCommand;
use App\Infrastructure\Telegram\StringInput;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DeleteRoom extends ManagerCommand
{
    public function signature(): string
    {
        return '/rmroom {token : Telegarm bot API token}';
    }

    public function description(): string
    {
        return 'Delete room and all data';
    }

    public function handle(StringInput $input): void
    {
        $user = $this->getManager();

        try {
            $room = $user->rooms()
                ->where('telegram_token', $input->getArgument('token'))
                ->firstOrFail();
            $room->delete();
            $this->bot->reply('Room deleted.');
        } catch (ModelNotFoundException $e) {
            $this->bot->reply('Room not found.');
        }
    }
}
