<?php

namespace App\Infrastructure\Telegram;

use App\Infrastructure\Telegram\Contracts\Api as ApiContract;
use App\Infrastructure\Telegram\Contracts\Command;
use App\Infrastructure\Telegram\Exceptions\TelegramWebhookException;
use App\Models\Room;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\BotMan\Http\Curl;
use BotMan\BotMan\Storages\Drivers\FileStorage;
use GuzzleHttp\ClientInterface;
use BotMan\BotMan\Cache\LaravelCache;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\HttpFoundation\Request;

class BotManager implements Contracts\BotManager
{
    private ClientInterface $http;
    private string $managerToken;
    /** @var Command[] */
    private array $managerCommands;
    /** @var Command[] */
    private array $roomCommands;
    private ApiContract $api;
    private array $middleware;

    public function __construct(
        ClientInterface $http,
        ApiContract $api,
        string $managerToken,
        array $managerCommands = [],
        array $roomCommands = [],
        array $middleware = []
    )
    {
        $this->http = $http;
        $this->managerToken = $managerToken;
        $this->managerCommands = $managerCommands;
        $this->roomCommands = $roomCommands;
        $this->api = $api;
        $this->middleware = $middleware;
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
            new CommandsManager($botMan, $this->api, $this->managerCommands),
            new MiddlewareManager($this->middleware)
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
            $this->api,
            $room,
            new CommandsManager($botMan, $this->api, $this->roomCommands),
            new MiddlewareManager($this->middleware)
        );
    }

    /**
     * Регистрация вебхука для комнаты
     * @param Room $room
     * @return string
     * @throws GuzzleException
     */
    public function registerWebhookForRoom(Room $room): string
    {
        return $this->registerWebhook(
            $room->telegram_token,
            route('telegram.webhook.room', $room)
        );
    }

    /**
     * Регистрация вебхука для менеджера
     * @throws GuzzleException
     */
    public function registerWebhookForManager(): string
    {
        return $this->registerWebhook(
            $this->managerToken,
            route('telegram.webhook.manager')
        );
    }

    /**
     * Регистрация вебхука для переданного токена и URL
     * @param string $token
     * @param string $url
     * @return string
     * @throws GuzzleException
     */
    protected function registerWebhook(string $token, string $url): string
    {
        $apiUrl = 'https://api.telegram.org/bot' . $token . '/setWebhook?url=' . $url;

        try {
            $this->http->request('get', $apiUrl);

            return $url;
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
        $config = [
            'telegram' => [
                'token' => $token,
                'default_additional_parameters' => [
                    'parse_mode' => 'markdown'
                ]
            ]
        ];

        $driverManager = new DriverManager($config, new Curl());

        return new BotMan(
            new LaravelCache(),
            $driverManager->getMatchingDriver(
                Request::createFromGlobals()
            ),
            $config,
            new FileStorage(storage_path('framework/cache')),
            new CommandMatcher()
        );
    }
}
