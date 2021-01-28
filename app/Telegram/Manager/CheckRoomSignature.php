<?php

namespace App\Telegram\Manager;

use App\Exceptions\OutOfDateSignature;
use App\Infrastructure\Telegram\ManagerCommand;
use App\Infrastructure\Telegram\StringInput;
use App\RoomSignatureManager;

class CheckRoomSignature extends ManagerCommand
{
    public function signature(): string
    {
        return sprintf(
            '/check {signature : %s}',
            trans('app.command.check_signature.signature')
        );
    }

    public function description(): string
    {
        return trans('app.command.check_signature.description');
    }

    public function handle(StringInput $input): void
    {
        $lock = $this->getUser()->getLock();

        if (!$lock->get()) {
            $this->bot->reply(
                trans('app.command.store_user_location.slow_down')
            );
            return;
        }

        $signature = $input->getArgument('signature');

        $manager = app(RoomSignatureManager::class);

        try {
            if ($manager->validate($signature, $this->getUser()->getHash())) {
                $this->bot->reply(
                    trans('app.command.check_signature.valid')
                );
                return;
            }
        } catch (OutOfDateSignature $e) {
            $this->bot->reply(
                trans('app.command.check_signature.out_of_date')
            );
            return;
        }

        $this->bot->reply(
            trans('app.command.check_signature.invalid')
        );
    }

    public function argsRules(): array
    {
        return [
            'signature' => ['string']
        ];
    }
}

