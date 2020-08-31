<?php

namespace App\Infrastructure\Telegram;

abstract class ManagerCommand extends Command
{
    /** @inheritDoc */
    public function group(): string
    {
        return trans('app.command.for_manager');
    }
}
