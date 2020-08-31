<?php

namespace App\Console\Commands\Telegram;

use App\Infrastructure\Telegram\Contracts\BotManager;
use App\Models\Room;
use Illuminate\Console\Command;

class RegisterManagerCommand extends Command
{
    protected $signature = 'telegram:register-webhooks';

    protected $description = 'Register telegram bot for managers';

    public function handle(BotManager $bots)
    {
        $url = $bots->registerWebhookForManager();

        $this->info(sprintf('Webhook [%s] registered.', $url));

        Room::get()->each(function (Room $room) use ($bots) {
            $url = $bots->registerWebhookForRoom($room);
            $this->info(sprintf('Webhook [%s] registered.', $url));
        });
    }
}
