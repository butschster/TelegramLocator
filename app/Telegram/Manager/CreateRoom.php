<?php

namespace App\Telegram\Manager;

use App\Infrastructure\Telegram\Contracts\BotManager;
use App\Infrastructure\Telegram\Exceptions\TelegramWebhookException;
use App\Infrastructure\Telegram\ManagerCommand;
use App\Infrastructure\Telegram\StringInput;
use App\Models\Room;
use App\Validation\Rules\TelegramToken;
use Exception;
use Telegram\Bot\Exceptions\TelegramSDKException;

class CreateRoom extends ManagerCommand
{
    public function signature(): string
    {
        return sprintf(
            '/new {token : %s}',
            trans('app.command.create_room.token')
        );
    }

    public function description(): string
    {
        return trans('app.command.create_room.description');
    }

    public function handle(StringInput $input): void
    {
        $token = $input->getArgument('token');
        $room = Room::where('telegram_token', $token)->first();

        if ($room) {
            $this->bot->reply(
                trans('app.command.create_room.room_exists')
            );
            return;
        }

        try {
            $bot = $this->api->getMe($token);
        } catch (TelegramSDKException $e) {
            $this->bot->reply(
                trans('app.command.create_room.invalid_token')
            );
            return;
        }

        $room = $this->getManager()->rooms()->create([
            'telegram_token' => $token,
            'name' => $bot->username,
            'is_anonymous' => false,
        ]);

        try {
            app(BotManager::class)->registerWebhookForRoom($room);

            $this->bot->reply(
                trans('app.command.create_room.registered', ['name' => $room->name])
            );
        } catch (TelegramWebhookException $e) {
            $this->bot->reply(
                trans('app.command.create_room.invalid_token')
            );
            $room->delete();
        }
    }

    public function argsRules(): array
    {
        return [
            'token' => [new TelegramToken()]
        ];
    }
}
