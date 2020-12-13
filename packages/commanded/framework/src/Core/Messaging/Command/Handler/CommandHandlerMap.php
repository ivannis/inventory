<?php

declare(strict_types=1);

namespace Commanded\Core\Messaging\Command\Handler;

use Commanded\Core\Messaging\Command\Metadata\CommandHandlerMetadata;

class CommandHandlerMap
{
    private array $container = [];

    public function __construct(array $commandMap)
    {
        foreach ($commandMap as $commandName => $commandHandler) {
            $this->register($commandName, $commandHandler);
        }
    }

    public function getHandler(string $commandName): callable
    {
        if (!isset($this->container[$commandName])) {
            throw new \RuntimeException(
                sprintf(
                    'Could not find a command handler for command "%s"',
                    $commandName
                )
            );
        }

        return $this->container[$commandName];
    }

    private function register(string $commandName, callable $commandHandler)
    {
        $this->container[$commandName] = $commandHandler;
    }
}
