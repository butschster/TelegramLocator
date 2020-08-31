<?php

namespace App\Telegram;

use App\Infrastructure\Telegram\ManagerCommand;
use App\Infrastructure\Telegram\LocationCommand;
use App\Infrastructure\Telegram\StringInput;
use Illuminate\Support\Collection;

class Commands extends ManagerCommand
{
    public function signature(): string
    {
        return '/commands';
    }

    public function description(): string
    {
        return 'List of available commands';
    }

    public function handle(StringInput $input): void
    {
        /** @var Collection $commands */
        $commands = $input->getArgument('commands');

        $text = "";

        foreach ($commands as $command) {
            if (!$command->isAllow($input)) {
                continue;
            }

            if ($command instanceof LocationCommand) {
                continue;
            }

            $text .= ltrim($command->name(), '/') . ' - ' . $command->description() . "\n";
        }

        $this->bot->reply($text);
    }
}

