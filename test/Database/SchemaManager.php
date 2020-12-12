<?php

declare(strict_types=1);

namespace HyperfTest\Database;

use Hyperf\Database\ConnectionInterface;
use Hyperf\Database\ConnectionResolverInterface;
use Hyperf\Database\Migrations\MigrationRepositoryInterface;
use Hyperf\Database\Migrations\Migrator;
use Hyperf\Database\Seeders\Seed;
use Psr\Container\ContainerInterface;

class SchemaManager
{
    private ContainerInterface $container;

    private Migrator $migrator;

    private Seed $seed;

    private MigrationRepositoryInterface $repository;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->migrator = $container->get(Migrator::class);
        $this->seed = $container->get(Seed::class);
        $this->repository = $container->get(MigrationRepositoryInterface::class);
    }

    public function call(string $command, array $options = [])
    {
        switch ($command) {
            case 'migrate:fresh':
                $options = array_merge([
                    'connection' => 'default',
                    'path' => 'migrations',
                    'drop-views' => false,
                    'seed' => false,
                ], $options);

                return $this->fresh(
                    $options['connection'],
                    $options['path'],
                    $options['drop-views'],
                    $options['seed'],
                );

            case 'migrate:install':
                $options = array_merge([
                    'connection' => 'default',
                ], $options);

                return $this->install($options['connection']);
            case 'migrate:reset':
                $options = array_merge([
                    'connection' => 'default',
                    'path' => 'migrations',
                ], $options);

                return $this->reset($options['connection'], $options['path']);
        }

        throw new \Exception('Unkow command ' . $command);
    }

    public function fresh(
        string $connection = 'default',
        string $path = 'migrations',
        bool $dropViews = false,
        bool $seedDB = false
    ) {
        if ($dropViews) {
            $this->dropAllViews($connection);
        }

        $this->dropAllTables($connection);

        $this->migrate($connection, $path, $seedDB);
    }

    public function migrate(string $connection = 'default', string $path = 'migrations', bool $seedDB = false)
    {
        $this->prepareDatabase($connection);
        $this->migrator->run($this->getMigrationPaths($path));

        if ($seedDB) {
            $this->seed($connection);
        }
    }

    public function reset(string $connection = 'default', string $path = 'migrations')
    {
        $this->migrator->setConnection($connection);
        $this->migrator->reset($this->getMigrationPaths($path));
    }

    public function seed(string $connection = 'default', string $path = 'seeders')
    {
        $this->seed->setConnection($connection);
        $this->seed->run([$this->getSeederPath()]);
    }

    public function getConnection(string $connection = 'default'): ConnectionInterface
    {
        return $this->container->get(ConnectionResolverInterface::class)
            ->connection($connection);
    }

    protected function prepareDatabase(string $connection)
    {
        $this->migrator->setConnection($connection);

        if (! $this->migrator->repositoryExists()) {
            $this->install($connection);
        }
    }

    protected function install(string $connection)
    {
        $this->repository->setSource($connection);
        $this->repository->createRepository();
    }

    /**
     * Drop all of the database tables.
     */
    protected function dropAllTables(string $connection)
    {
        $this->container->get(ConnectionResolverInterface::class)
            ->connection($connection)
            ->getSchemaBuilder()
            ->dropAllTables();
    }

    /**
     * Drop all of the database views.
     */
    protected function dropAllViews(string $connection)
    {
        $this->container->get(ConnectionResolverInterface::class)
            ->connection($connection)
            ->getSchemaBuilder()
            ->dropAllViews();
    }

    protected function getMigrationPaths(string $path): array
    {
        return array_merge(
            $this->migrator->paths(),
            [$this->getMigrationPath($path)]
        );
    }

    protected function getMigrationPath(string $path): string
    {
        return BASE_PATH . DIRECTORY_SEPARATOR . $path;
    }

    protected function getSeederPath(string $path)
    {
        return BASE_PATH . DIRECTORY_SEPARATOR . $path;
    }
}
