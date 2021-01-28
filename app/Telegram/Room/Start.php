<?php

namespace App\Telegram\Room;

use App\Infrastructure\Telegram\Command;
use App\Infrastructure\Telegram\StringInput;
use App\Models\Room;
use App\RoomSignatureManager;

class Start extends Command
{
    protected bool $showHelp = false;

    public function signature(): string
    {
        return '/start';
    }

    public function description(): string
    {
        return '';
    }

    public function handle(StringInput $input): void
    {
        /** @var Room $room */
        $room = $input->getArgument('room');

        $this->bot->reply(trans('app.command.start.message', [
            'username' => $room->is_anonymous ? 'anonymous' : $this->getUser()->getUsername(),
            'type' => $room->is_anonymous ? 'anonymous' : 'public',
            'signature' => $room->signature($this->getUser()->getHash()),
            'bot' => config('telegram.manager.bot_name') ?? ''
        ]));
    }
}
