<?php

namespace App\Console\Commands\Telegram;

use App\Infrastructure\Telegram\Contracts\BotManager;
use App\Models\Room;
use Illuminate\Console\Command;

class RegisterRoomCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:register-room {room}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Register telegram bot for room';

    public function handle(BotManager $bots)
    {
        $room = Room::findOrFail($this->argument('room'));

        $bots->registerWebhookForRoom($room);
    }
}
