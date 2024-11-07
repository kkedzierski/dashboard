<?php

require_once __DIR__ . '/../../../vendor/autoload.php';

use App\Account\Application\CreateUserService;
use App\Account\Application\Exception\UserExistException;
use App\Account\Domain\RoleEnum;
use App\Account\Domain\UserFactory;
use App\Account\Infrastructure\UserRepository;
use App\Account\Ui\CreateUserDto;
use App\Kernel\Clock\Clock;
use App\Kernel\Database\PdoProvider;
use App\Kernel\Database\QueryBuilder;
use App\Kernel\HashPassword\HashPasswordManager;
use App\Kernel\Logger\Logger;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable('/var/www/server/');
$dotenv->load();

$createUserService = provideCreateUserService();

$email = $argv[1] ?? null;
$username = $argv[2] ?? null;
$password = $argv[3] ?? null;

if (!$email || !$username || !$password) {
    echo "Usage: php CreateUserCommand.php [email] [username] [password]\n";
    exit(1);
}

try {
    $createUserDto = new CreateUserDto($email, $username, $password, [RoleEnum::ADMIN]);
    $createUserService->createUser($createUserDto);

    echo "User created successfully\n";
} catch (UserExistException $e) {
    echo $e->getMessage();
    exit(1);
}

function provideCreateUserService(): CreateUserService
{
    $clock = new Clock();
    $logger = new Logger($clock, $_ENV['LOG_DIR'], $_ENV['ENV']);
    $hashPasswordManager = new HashPasswordManager();
    $userFactory = new UserFactory($hashPasswordManager);
    $pdoProvider = new PdoProvider($logger, $_ENV['DATABASE_DSN'], $_ENV['DB_USER'], $_ENV['DB_PASS']);
    $queryBuilder = new QueryBuilder($pdoProvider);
    $userRepository = new UserRepository($queryBuilder);

    return new CreateUserService($userFactory, $userRepository, $logger);
}
