<?php

namespace App\Infrastructure\Telegram;

use App\Infrastructure\Telegram\Contracts\Command;
use App\Infrastructure\Telegram\Exceptions\TelegramWebhookException;
use App\Models\Room;
use BotMan\BotMan\BotManFactory;
use GuzzleHttp\ClientInterface;
use BotMan\BotMan\Cache\LaravelCache;
use GuzzleHttp\Exception\ClientException;

class BotManager implements Contracts\BotManager
{
    private ClientInterface $http;
    private string $managerToken;
    /** @var Command[] */
    private array $managerCommands;
    /** @var Command[] */
    private array $roomCommands;

    public function __construct(
        ClientInterface $http,
        string $managerToken,
        array $managerCommands = [],
        array $roomCommands = []
    )
    {
        $this->http = $http;
        $this->managerToken = $managerToken;
        $this->managerCommands = $managerCommands;
        $this->roomCommands = $roomCommands;
    }

    public function forManager(): Contracts\ManagerBot
    {
        $botMan = BotManFactory::create([
            'telegram' => [
                'token' => $this->managerToken
            ]
        ], new LaravelCache());

        return new ManagerBot(
            $botMan,
            new CommandsManager($botMan, $this->managerCommands)
        );
    }

    public function forRoom(Room $room): Contracts\RoomBot
    {
        $botMan = BotManFactory::create([
            'telegram' => [
                'token' => $room->telegram_token
            ]
        ], new LaravelCache());

        return new RoomBot(
            $botMan,
            $room,
            new CommandsManager($botMan, $this->roomCommands)
        );
    }

    public function registerWebhookForRoom(Room $room): void
    {
        $this->registerWebhook(
            $room->telegram_token,
            route('telegram.webhook.room', $room)
        );
    }

    public function registerWebhookForManager(): void
    {
        $this->registerWebhook(
            $this->managerToken,
            route('telegram.webhook.manager')
        );
    }

    protected function registerWebhook(string $token, string $url)
    {
        $url = 'https://api.telegram.org/bot' . $token . '/setWebhook?url=' . $url;

        try {
            $this->http->request('get', $url);
        } catch (ClientException $e) {
            throw new TelegramWebhookException($e->getMessage());
        }
    }
}
