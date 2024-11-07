<?php

namespace App\Kernel\Container;

use App\Account\Application\CreateUserService;
use App\Account\Domain\UserFactory;
use App\Account\Domain\UserRepositoryInterface;
use App\Account\Infrastructure\UserRepository;
use App\Kernel\Clock\Clock;
use App\Kernel\Clock\ClockInterface;
use App\Kernel\Database\Migration\MigrationManager;
use App\Kernel\Database\PdoProvider;
use App\Kernel\Database\PdoProviderInterface;
use App\Kernel\Database\QueryBuilder;
use App\Kernel\HashPassword\HashPasswordManager;
use App\Kernel\HashPassword\HashPasswordManagerInterface;
use App\Kernel\Logger\Logger;
use App\Kernel\Logger\LoggerInterface;
use App\Kernel\Router\RegisterRouteManager;
use App\Kernel\Router\Router;
use Psr\Container\ContainerInterface;

readonly class ContainerServicesManager
{
    public function __construct(
        private ContainerInterface $container,
    ) {
    }

    public function registerServices(): void
    {
        $this->registerKernelServices();
        $this->registerUserDomainServices();
        $this->registerNewsPostDomainServices();
    }

    private function registerKernelServices(): void
    {
        $this->container->set(ClockInterface::class, fn() => new Clock());
        $this->container->set(LoggerInterface::class, fn() => new Logger(
            $this->container->get(ClockInterface::class),
            $_ENV['LOG_DIR'],
            $_ENV['ENV'],
        ));
        $this->container->set(Router::class, fn() => new Router(
            $this->container->get(LoggerInterface::class),
        ));
        $this->container->set(RegisterRouteManager::class, fn() => new RegisterRouteManager(
            $this->container->get(Router::class),
        ));
        $this->container->set(HashPasswordManagerInterface::class, fn() => new HashPasswordManager());

        $this->registerDatabaseServices();
    }

    private function registerDatabaseServices(): void
    {
        $this->container->set(PdoProviderInterface::class, fn() => new PdoProvider(
            $this->container->get(LoggerInterface::class),
            $_ENV['DATABASE_DSN'],
            $_ENV['DB_USER'],
            $_ENV['DB_PASS'],
        ));
        $this->container->set(QueryBuilder::class, fn() => new QueryBuilder(
            $this->container->get(PdoProviderInterface::class),
        ));
        $this->container->set(MigrationManager::class, fn() => new MigrationManager(
            $this->container->get(PdoProviderInterface::class),
        ));
    }

    private function registerUserDomainServices(): void
    {
        $this->container->set(UserFactory::class, fn() => new UserFactory(
            $this->container->get(HashPasswordManagerInterface::class),
        ));
        $this->container->set(UserRepositoryInterface::class, fn() => new UserRepository(
            $this->container->get(PdoProviderInterface::class),
        ));
        $this->container->set(CreateUserService::class, fn() => new CreateUserService(
            $this->container->get(UserFactory::class),
            $this->container->get(UserRepositoryInterface::class),
            $this->container->get(LoggerInterface::class),
        ));
    }

    private function registerNewsPostDomainServices(): void
    {

    }
}
