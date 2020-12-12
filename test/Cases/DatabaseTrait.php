<?php

declare(strict_types=1);

namespace HyperfTest\Cases;

use Hyperf\Utils\ApplicationContext;
use HyperfTest\Database\SchemaManager;

trait DatabaseTrait
{
    protected function setUp()
    {
        $container = ApplicationContext::getContainer();
        $schemaManager = $container->get(SchemaManager::class);

        $schemaManager->getConnection('default')->beginTransaction();
        $schemaManager->call('migrate:fresh', [
            'connection' => 'default',
        ]);
    }

    protected function tearDown()
    {
        $container = ApplicationContext::getContainer();
        $schemaManager = $container->get(SchemaManager::class);

        $schemaManager->getConnection('default')->rollBack();
    }
}
