<?php

namespace App\Telegram\Manager;

use App\Infrastructure\Telegram\ManagerCommand;
use App\Models\User;

class RegisterUser extends ManagerCommand
{
    public function signature(): string
    {
        return '/register';
    }

    public function description(): string
    {
        return 'Register new user';
    }

    public function handle(): void
    {
        $user = User::find($this->bot->getUser()->getId());

        if ($user) {
            $this->bot->reply('Your account has been already registered.');
            return;
        }

        $user = User::create([
            'id' => $this->getUserHash(),
            'username' => $this->bot->getUser()->getUsername()
        ]);

        $this->bot->reply(sprintf('Hello %s! Welcome to our service!', $user->username));
    }
}
