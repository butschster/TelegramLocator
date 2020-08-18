<?php

namespace App\Telegram\Room;

use App\Infrastructure\Telegram\Command;
use App\Infrastructure\Telegram\StringInput;
use App\Models\Room;
use Hash;
use Illuminate\Auth\Access\AuthorizationException;

class AuthUser extends Command
{
    public function signature(): string
    {
        return sprintf(
            '/auth {password : %s}',
            trans('app.command.room_auth.password')
        );
    }

    public function description(): string
    {
        return trans('app.command.room_auth.description');
    }

    public function handle(StringInput $input): void
    {
        /** @var Room $room */
        $room = $input->getArgument('room');
        $password = $input->getArgument('password');
        $user = $this->getUser();

        if ($room->hasAccess($user)) {
            $this->bot->reply(
                trans('app.command.room_auth.auth_not_require')
            );
            return;
        }

        if (!Hash::check($password, $room->password)) {
            throw new AuthorizationException(
                trans('app.command.room_auth.incorrect_password')
            );
        }

        $room->addUser($user);
        $this->bot->reply(
            trans('app.command.room_auth.authenticated')
        );
    }

    public function argsRules(): array
    {
        return [
            'password' => ['required', 'string', 'min:1'],
        ];
    }
}
