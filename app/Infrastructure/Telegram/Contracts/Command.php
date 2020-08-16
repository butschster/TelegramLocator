<?php

namespace App\Infrastructure\Telegram\Contracts;

use App\Infrastructure\Telegram\StringInput;
use Symfony\Component\Console\Input\InputInterface;

interface Command
{
    /**
     * Название команды канала
     * @return string
     */
    public function name(): string;

    /**
     * Строка команды с описанием аргументов
     * @return string
     */
    public function signature(): string;

    /**
     * Строка команды без описания аргументов
     * @return string
     */
    public function pattern(): string;

    /**
     * Описание команды
     * @return string
     */
    public function description(): string;

    /**
     * Доступ только для владельца
     * @return bool
     */
    public function forManager(): bool;

    /**
     * Запуск команды
     * @param StringInput $input
     */
    public function handle(StringInput $input): void;

    /**
     * Текст помощи
     * @return string
     */
    public function help(): string;

    /**
     * Входные аргументы
     * @return InputInterface
     */
    public function args(): InputInterface;
}
