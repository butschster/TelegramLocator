<?php

namespace App\Telegram\Room;

use App\Infrastructure\Telegram\ManagerCommand as BaseManagerCommand;
use App\Infrastructure\Telegram\StringInput;
use App\Models\Room;

abstract class ManagerCommand extends BaseManagerCommand
{
    /** @inheritDoc */
    public function isAllow(StringInput $input): bool
    {
        try {
            /** @var Room $room */
            $room = $input->getArgument('room');
            return $room->isOwner($this->getUser());
        } catch (\Symfony\Component\Console\Exception\InvalidArgumentException $e) {}

        return false;
    }
}
