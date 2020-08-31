<?php

namespace App\Infrastructure\Telegram\Exceptions;

use RuntimeException;

class NotEnoughArgumentsException extends RuntimeException
{
    private array $arguments;

    public function __construct(string $message, array $arguments)
    {
        parent::__construct($message, 0);
        $this->arguments = $arguments;
    }

    /**
     * @return array
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }
}
