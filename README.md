# Panel dashboard
Clean php and clean javascript project
designed as a React-like framework imitation and Symfony-like framework.

## Used solutions:
#### Docker:
The applications are run in Docker containers, allowing easy deployment on any operating system.
#### Client side:
##### - vanilla JavaScript, HTML, CSS
##### - [Atomic design](https://bradfrost.com/blog/post/atomic-web-design/)
##### - [Web components](https://developer.mozilla.org/en-US/docs/Web/Web_Components)
The Atomic Design approach has been applied, enabling modular development of web applications.
The client-side application communicates with the API server using a JWT token returned by the server.
It was built in plain JavaScript, without frameworks, which allows for a fast application.

#### Server side:
##### - PHP 8.0, PHPUnit
The server was also developed in plain PHP, without frameworks, allowing for a fast application.

- Developed using the DDD (Domain-Driven Design) approach
- Implemented migrations
- Unit tests have been implemented
- Implemented API
- Implemented JWT authorization
- Implemented error handling
- Implemented logging


## Full documentation:
1. Run:
```sh
bash documentation/deploy_docs.sh
```
2. And go to: http://127.0.0.1:8000/dashboard/

## Installation quick guide

### Requirements

To run this project, you need to have Docker and Docker Compose installed. Below are instructions on how to check if these tools are installed and how to install them if they are missing.

### Checking Docker Installation

To check if Docker is installed on your system, run the following command in your terminal:

```sh
docker --version
```

If Docker Compose is not installed, follow the instructions [on the official Docker Compose](https://docs.docker.com/get-docker/) website to install it.

### Checking Docker Compose Installation

To check if Docker Compose is installed on your system, run the following command in your terminal:

```sh
docker-compose --version
```

If Docker Compose is not installed, follow the instructions [on the official Docker Compose](https://docs.docker.com/compose/install/) website to install it.

### Running the project

To run the project, clone the repository and run the following commands in the project directory:

1. clone the repository

```sh
git clone git@github.com:kkedzierski/dashboard.git
cd dashboard
```

2. Copy /docker/.env.dist to /docker/.env and set the values

Example .env file:
```sh
CONTAINER_NAME="dashboard-php"
DOCKER_MYSQL_USER="user"
DOCKER_MYSQL_PASSWORD="password"
DOCKER_MYSQL_ROOT_PASSWORD="password"
DOCKER_MYSQL_DB="dashboard"
DOCKER_MYSQL_DB_TEST="dashboard_test"
DOCKER_NGINX_CLIENT_PORT="26500"
DOCKER_NGINX_SERVER_PORT="26501"
DOCKER_PHP_PORT="26502"
DOCKER_MYSQL_PORT="26503"
DOCKER_MYSQL_TEST_PORT="26504"

```

3. Copy /server/.env.dist to /server/.env and set the values

Example .env file:
```sh
ENV=dev

###> database ###
DB_HOST=dashboard-mysql
DB_NAME=dashboard
DB_USER=user
DB_PASS=password
DATABASE_DSN=mysql:host=${DB_HOST};dbname=${DB_NAME};port=3306
###< database ###

###> logger ####
LOG_DIR=/var/www/server/var/log
###< logger ###

###> api ###
API_PREFIX=/api/v1
###< api ###

###> jwt ###
JWT_SECRET='8rf0tVJngkIktFUofu3LwyzJ0cpIJ5p7qboHml/9rrY='
JWT_EXPIRATION=3600
###< jwt ###
```

4. Run the project

```sh
bash docker/run-dashboard.sh
```

5. Application will be available at http://localhost:26500
