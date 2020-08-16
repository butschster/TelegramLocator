<?php

namespace App\Telegram\Manager;

use App\Infrastructure\Telegram\Contracts\BotManager;
use App\Infrastructure\Telegram\Exceptions\TelegramWebhookException;
use App\Infrastructure\Telegram\ManagerCommand;
use App\Infrastructure\Telegram\StringInput;
use App\Models\Room;
use Exception;

class CreateRoom extends ManagerCommand
{
    public function signature(): string
    {
        return '/new {token : Telegram bot API token}';
    }

    public function description(): string
    {
        return 'Create a new room';
    }

    public function handle(StringInput $input): void
    {
        $token = $input->getArgument('token');
        $room = Room::where('telegram_token', $token)->first();

        if ($room) {
            $this->bot->reply('Room with given token exists.');
            return;
        }

        $room = $this->getManager()->rooms()->create([
            'telegram_token' => $token,
            'is_anonymous' => false,
        ]);

        try {
            app(BotManager::class)->registerWebhookForRoom($room);

            $this->bot->reply('Room successful registered.');
        } catch (TelegramWebhookException $e) {
            $this->bot->reply('Invalid telegram bot token. Please try again!');
            $room->delete();
        } catch (Exception $e) {
            $this->bot->reply('Something went wrong.');
        }
    }
}
