<?php

namespace App\Infrastructure\Telegram;

use App\Infrastructure\Telegram\Contracts\Command;
use App\Infrastructure\Telegram\Exceptions\TelegramWebhookException;
use App\Models\Room;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use GuzzleHttp\ClientInterface;
use BotMan\BotMan\Cache\LaravelCache;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;

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

    /**
     * Создание бота для менеджера
     *
     * @return Contracts\Bot
     */
    public function forManager(): Contracts\Bot
    {
        $botMan = $this->createBotManInstance($this->managerToken);

        return new ManagerBot(
            $botMan,
            new CommandsManager($botMan, $this->managerCommands)
        );
    }

    /**
     * Создание бота для комнаты
     *
     * @param Room $room
     * @return Contracts\Bot
     */
    public function forRoom(Room $room): Contracts\Bot
    {
        $botMan = $this->createBotManInstance($room->telegram_token);

        return new RoomBot(
            $botMan,
            $room,
            new CommandsManager($botMan, $this->roomCommands)
        );
    }

    /**
     * Регистрация вебхука для комнаты
     * @param Room $room
     * @throws GuzzleException
     */
    public function registerWebhookForRoom(Room $room): void
    {
        $this->registerWebhook(
            $room->telegram_token,
            route('telegram.webhook.room', $room)
        );
    }

    /**
     * Регистрация вебхука для менеджера
     * @throws GuzzleException
     */
    public function registerWebhookForManager(): void
    {
        $this->registerWebhook(
            $this->managerToken,
            route('telegram.webhook.manager')
        );
    }

    /**
     * Регистрация вебхука для переданного токена и URL
     * @param string $token
     * @param string $url
     * @throws GuzzleException
     */
    protected function registerWebhook(string $token, string $url): void
    {
        $url = 'https://api.telegram.org/bot' . $token . '/setWebhook?url=' . $url;

        try {
            $this->http->request('get', $url);
        } catch (ClientException $e) {
            throw new TelegramWebhookException($e->getMessage());
        }
    }

    /**
     *
     * @param string $token
     * @return BotMan
     */
    protected function createBotManInstance(string $token): BotMan
    {
        return BotManFactory::create([
            'telegram' => [
                'token' => $token,
                'default_additional_parameters' => [
                    'parse_mode' => 'markdown'
                ]
            ]
        ], new LaravelCache());
    }
}
