<?php

namespace App\Infrastructure\Telegram;

use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputDefinition;

class StringInput extends ArgvInput
{
    const REGEX_STRING = '([^\s]+?)(?:\s|(?<!\\\\)"|(?<!\\\\)\'|$)';
    const REGEX_QUOTED_STRING = '(?:"([^"\\\\]*(?:\\\\.[^"\\\\]*)*)"|\'([^\'\\\\]*(?:\\\\.[^\'\\\\]*)*)\')';

    /**
     * @param string $input A string representing the parameters from the CLI
     * @param InputDefinition $definition
     */
    public function __construct(string $input, InputDefinition $definition)
    {
        $tokens = $this->tokenize($input);

        // strip the application name
        array_shift($tokens);
        if ($definition->getArgumentCount() === 1) {
            $this->setTokens($tokens = [implode(' ', $tokens)]);
        }

        $this->bind($definition);
        $this->validate();
    }

    /** {@inheritdoc} */
    public function getArgument(string $name)
    {
        if (!isset($this->arguments[$name]) && !$this->definition->hasArgument($name)) {
            throw new \Symfony\Component\Console\Exception\InvalidArgumentException(sprintf('The "%s" argument does not exist.', $name));
        }

        return isset($this->arguments[$name]) ? $this->arguments[$name] : $this->definition->getArgument($name)->getDefault();
    }

    /** @inheritdoc} */
    public function setArgument(string $name, $value)
    {
        $this->arguments[$name] = $value;
    }

    /**
     * Tokenizes a string.
     *
     * @param string $input
     * @return array
     */
    private function tokenize(string $input): array
    {
        $tokens = [];
        $length = \strlen($input);
        $cursor = 0;
        while ($cursor < $length) {
            if (preg_match('/\s+/A', $input, $match, null, $cursor)) {

            } elseif (preg_match('/([^="\'\s]+?)(=?)(' . self::REGEX_QUOTED_STRING . '+)/A', $input, $match, null, $cursor)) {
                $tokens[] = $match[1] . $match[2] . stripcslashes(str_replace(['"\'', '\'"', '\'\'', '""'], '', substr($match[3], 1, \strlen($match[3]) - 2)));
            } elseif (preg_match('/' . self::REGEX_QUOTED_STRING . '/A', $input, $match, null, $cursor)) {
                $tokens[] = stripcslashes(substr($match[0], 1, \strlen($match[0]) - 2));
            } elseif (preg_match('/' . self::REGEX_STRING . '/A', $input, $match, null, $cursor)) {
                $tokens[] = stripcslashes($match[1]);
            } else {
                // should never happen
                throw new InvalidArgumentException(sprintf('Unable to parse input near "... %s ...".', substr($input, $cursor, 10)));
            }

            $cursor += \strlen($match[0]);
        }

        return $tokens;
    }

    /**
     * @param Contracts\Command $command
     * @throws \Illuminate\Validation\ValidationException
     */
    public function validateCommand(Contracts\Command $command)
    {
        Validator::make($this->getArguments(), $command->argsRules())->validate();
    }
}
