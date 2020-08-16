<?php

namespace App\Infrastructure\Telegram;

class User
{
    private int $userId;
    private string $username;
    private string $hash;

    public function __construct(int $userId, string $username)
    {
        $this->userId = $userId;
        $this->username = $username;
        $this->hash = hash_hmac('sha1', $this->userId, config('app.key'));
    }

    /**
     * Уникальный хеш пользователя
     * @return string
     */
    public function getHash(): string
    {
        return $this->hash;
    }

    /**
     * Username пользователя
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }
}
