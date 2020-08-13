<?php

namespace App\Infrastructure\Telegram;

abstract class ManagerCommand extends Command
{
    public function forManager(): bool
    {
        return true;
    }
}
