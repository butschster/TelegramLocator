<?php

namespace App\Infrastructure\Telegram\Contracts;

interface Command
{
    public function signature(): string;
    public function description(): string;
    public function forManager(): bool;
}
