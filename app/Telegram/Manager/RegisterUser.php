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
        return 'Register new user';
    }

    public function handle(StringInput $input): void
    {
        $user = User::findByTelegramUser($this->getUser());

        if ($user) {
            $this->bot->reply('Your account has been already registered.');
            return;
        }

        $user = User::create([
            'id' => $this->getUser()->getHash(),
            'username' => $this->getUser()->getUsername()
        ]);

        $this->bot->reply(sprintf('Hello %s! Welcome to our service!', $user->username));
    }
}
