<?php

namespace App\Telegram\Manager;

use App\Infrastructure\Telegram\ManagerCommand;
use App\Infrastructure\Telegram\StringInput;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DeleteRoom extends ManagerCommand
{
    public function signature(): string
    {
        return sprintf(
            '/del {token : %s}',
            trans('app.command.delete_room.token')
        );
    }

    public function description(): string
    {
        return trans('app.command.delete_room.description');
    }

    public function handle(StringInput $input): void
    {
        $user = $this->getManager();

        try {
            $room = $user->rooms()
                ->where('telegram_token', $input->getArgument('token'))
                ->firstOrFail();
            $room->delete();
            $this->bot->reply(
                trans('app.command.delete_room.deleted')
            );
        } catch (ModelNotFoundException $e) {
            $this->bot->reply(
                trans('app.command.delete_room.room_not_found')
            );
        }
    }
}
