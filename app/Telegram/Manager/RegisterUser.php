<?php

namespace App\Telegram\Manager;

use App\Infrastructure\Telegram\Command;
use App\Infrastructure\Telegram\StringInput;
use App\Models\User;

class RegisterUser extends Command
{
    public function signature(): string
    {
        return '/register';
    }

    public function description(): string
    {
        return trans('app.command.manager.register');
    }

    public function handle(StringInput $input): void
    {
        $user = User::findByTelegramUser($this->getUser());

        if ($user) {
            $this->bot->reply(
                trans('app.command.manager.account_exists')
            );

            return;
        }

        User::create([
            'id' => $this->getUser()->getHash()
        ]);

        $this->bot->reply(
            trans('app.command.manager.registered', ['username' => $this->getUser()->getUsername()])
        );
    }
}
