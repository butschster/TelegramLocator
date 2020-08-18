<?php

namespace App\Infrastructure\Telegram;

use BotMan\BotMan\Messages\Attachments\Location;

abstract class LocationCommand extends Command
{
    public function signature(): string
    {
        return Location::PATTERN;
    }

    /**
     * TODO: привести в рабочее состояние
     * Разрешена отправка только текущего местоположения
     * @return bool
     */
    public function allowedOnlyCurrent(): bool
    {
        return true;
    }

    /** @inheritDoc */
    public function help(): string
    {
        $command = "*{$this->description()}*\n```\n";

        $command .= trans('app.command.location.help');

        return $command . "```" . "\n";
    }

}
