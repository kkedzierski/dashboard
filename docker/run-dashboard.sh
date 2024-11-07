#!/usr/bin/env bash

environment=dev
rebuilt=false

source ./docker/.env

while getopts e:r: flag
do
    case "${flag}" in
        e) environment=${OPTARG};;
        r) rebuilt=${OPTARG};;
    esac
done

if [ "$rebuilt" == "true" ]; then
  echo "Rebuilding configuration image..."
  docker build --no-cache . -f ./docker/etc/php/main.Dockerfile
fi

ENV="./docker/.env.dist"
if [ -f ./docker/.env ]; then
    ENV="./docker/.env"
fi

BASE_DIRECTORY=$(pwd)/server

echo "Building and starting containers..."
docker-compose down
docker-compose --env-file $ENV up -d --build
echo "Containers are up and running."

echo "Installing composer dependencies..."
docker exec -it "${CONTAINER_NAME}" composer install
echo "Composer dependencies installed."

echo "Creating database schema..."
docker exec -it "${CONTAINER_NAME}" php src/Kernel/Database/Migration/MakeMigrationCommand.php up

echo "Creating test database schema..."
docker exec -it "${CONTAINER_NAME}" php src/Account/Ui/CreateUserCommand.php admin@email.com admin test
