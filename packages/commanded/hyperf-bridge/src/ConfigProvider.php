<?php

declare(strict_types=1);

namespace Commanded\HyperfBridge;

use Commanded\Core\Messaging\Command\CommandHandler;
use Commanded\Core\Messaging\Command\Handler\CommandHandlerMap;
use Commanded\Core\Messaging\CommandBus;
use Commanded\Core\Messaging\Event\EventHandler;
use Commanded\Core\Messaging\Event\Handler\EventHandlerMap;
use Commanded\Core\Messaging\Event\Recorder\EventRecorder;
use Commanded\Core\Messaging\EventBus;
use Commanded\Core\Messaging\Middleware\CommandHandlerMiddleware;
use Commanded\Core\Messaging\Middleware\EventHandlerMiddleware;
use Commanded\Core\Messaging\Middleware\QueryHandlerMiddleware;
use Commanded\Core\Messaging\Middleware\RecordedEventsMiddleware;
use Commanded\Core\Messaging\Query\Handler\QueryHandlerMap;
use Commanded\Core\Messaging\Query\QueryHandler;
use Commanded\Core\Messaging\QueryBus;
use Commanded\Domain\MessageBus;
use Psr\Container\ContainerInterface;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
                CommandHandlerMap::class => function(ContainerInterface $container) {
                    return new CommandHandlerMap($container->get('command_handlers'));
                },
                QueryHandlerMap::class => function(ContainerInterface $container) {
                    return new QueryHandlerMap($container->get('query_handlers'));
                },
                EventHandlerMap::class => function(ContainerInterface $container) {
                    return new EventHandlerMap($container->get('event_handlers'));
                },
                CommandBus::class => function(ContainerInterface $container) {
                    return new CommandBus(
                        new CommandHandlerMiddleware($container->get(CommandHandler::class)),
                        new RecordedEventsMiddleware(
                            $container->get(EventRecorder::class),
                            $container->get(EventBus::class)
                        ),
                    );
                },
                QueryBus::class => function(ContainerInterface $container) {
                    return new QueryBus(
                        new QueryHandlerMiddleware($container->get(QueryHandler::class))
                    );
                },
                EventBus::class => function(ContainerInterface $container) {
                    return new EventBus(
                        new EventHandlerMiddleware($container->get(EventHandler::class))
                    );
                },
                MessageBus::class => function(ContainerInterface $container) {
                    return new MessageBus(
                        $container->get(CommandBus::class),
                        $container->get(QueryBus::class),
                        $container->get(EventBus::class)
                    );
                },
            ],
        ];
    }
}
