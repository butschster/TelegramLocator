<?php

namespace App\Infrastructure\Telegram;

use BotMan\BotMan\BotMan;
use Illuminate\Support\Collection;
use BotMan\BotMan\Interfaces\Middleware\Received;
use BotMan\BotMan\Interfaces\Middleware\Captured;
use BotMan\BotMan\Interfaces\Middleware\Matching;

class MiddlewareManager
{
    /**
     * @var Collection|\Tightenco\Collect\Support\Collection|Command[]
     */
    private Collection $middleware;

    public function __construct(array $middleware)
    {
        $this->middleware = collect($middleware)->map(function ($middleware) {
            return app($middleware);
        });
    }

    public function register(BotMan $botMan)
    {
        $this->middleware->each(function ($middleware) use($botMan) {
            if ($middleware instanceof Received) {
                $botMan->middleware->received($middleware);
                return;
            }

            if ($middleware instanceof Captured) {
                $botMan->middleware->captured($middleware);
                return;
            }

            if ($middleware instanceof Matching) {
                $botMan->middleware->matching($middleware);
                return;
            }
        });
    }
}
