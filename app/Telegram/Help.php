<?php

namespace App\Telegram;

use App\Infrastructure\Telegram\Command;
use App\Infrastructure\Telegram\StringInput;
use Illuminate\Support\Collection;

class Help extends Command
{
    protected bool $showHelp = false;

    public function signature(): string
    {
        return '/help';
    }

    public function description(): string
    {
        return trans('app.command.help.description');
    }

    public function handle(StringInput $input): void
    {
        /** @var Collection $commands */
        $commands = $input->getArgument('commands');

        $text = "";

        $groupedCommands = $commands
            ->filter(function ($command) use($input) {
                return $command->isAllow($input);
            })
            ->groupBy(function ($command) {
                return $command->group();
            });

        foreach ($groupedCommands as $group => $list) {
            $text .= "\n*{$group}*\n---\n";
            foreach ($list as $command) {
                $text .= $command->help();
            }
        }

        if (!empty($text)) {
            $this->bot->reply($text);
        } else {
            $this->bot->reply(trans('app.command.empty_list_of_commands'));
        }
    }
}
