<?php

namespace App\Telegram\Room;

use App\Infrastructure\Telegram\Command;
use App\Infrastructure\Telegram\StringInput;
use App\Models\Room;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SendMessage extends Command
{
    public function signature(): string
    {
        return sprintf(
            '/msg {message : %s}',
            trans('app.command.send_message.message')
        );
    }

    public function description(): string
    {
        return trans('app.command.send_message.description');
    }

    public function handle(StringInput $input): void
    {
        /** @var Room $room */
        $room = $input->getArgument('room');

        try {
            $point = Room\Point::findByUser($room, $this->getUser());

            $point->update([
                'message' => $input->getArgument('message')
            ]);

            $this->bot->reply(
                trans('app.command.send_message.sent')
            );

        } catch (ModelNotFoundException $e) {
            $this->bot->reply(
                trans('app.command.send_message.point_not_found')
            );
        }
    }
}
