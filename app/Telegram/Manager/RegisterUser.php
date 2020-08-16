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
        return 'Register as a manager';
    }

    public function handle(StringInput $input): void
    {
        $user = User::findByTelegramUser($this->getUser());

        if ($user) {
            $this->bot->reply('Your account has been already registered.');
            return;
        }

        User::create([
            'id' => $this->getUser()->getHash()
        ]);

        $this->bot->reply(sprintf(
            'Hello %s! Welcome to our service! We appreciate your privacy and that\'s why we don\'t store any information about you.',
            $this->getUser()->getUsername()
        ));
    }
}
