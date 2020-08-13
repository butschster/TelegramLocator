<?php

namespace App\Console\Commands\Telegram;

use App\Infrastructure\Telegram\Contracts\BotManager;
use App\Models\Room;
use Illuminate\Console\Command;

class RegisterManagerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:register-manager';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Register telegram bot for managers';

    public function handle(BotManager $bots)
    {
        $bots->registerWebhookForManager();
    }
}
