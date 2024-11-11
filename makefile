.DEFAULT_GOAL := help

help:  ## Display this help
	@awk 'BEGIN {FS = ":.*##"; printf "\nUsage:\n  make \033[36m<target>\033[0m\n"} /^[.a-zA-Z_-]+:.*?##/ { printf "  \033[36m%-15s\033[0m %s\n", $$1, $$2 } /^##@/ { printf "\n\033[1m%s\033[0m\n", substr($$0, 5) } ' $(MAKEFILE_LIST)

CONTAINER_NAME = dashboard-php
NODE_CONTAINER_NAME = dashboard-node

.PHONY: exec-root
exec-root: ## Shell into container
	docker exec -it -u root $(CONTAINER_NAME) /bin/bash

.PHONY: unit tests
phpunit: ## unit tests
	docker exec -it -u root $(CONTAINER_NAME) ./vendor/bin/phpunit
unit-test: phpunit ## Alias for mutation
phpunit-test: phpunit ## Alias for mutation
test-unit: phpunit ## Alias for mutation
tests-unit: phpunit ## Alias for mutation

.PHONY: migrations up
migrations-up: ## Run migrations
	docker exec -it -u root $(CONTAINER_NAME) php src/Kernel/Database/Migration/MakeMigrationCommand.php up
m-u: migrations-up ## Alias for migrations-down

.PHONY: migrations down
migrations-down: ## Rollback migrations
	docker exec -it -u root $(CONTAINER_NAME) php src/Kernel/Database/Migration/MakeMigrationCommand.php down
m-d: migrations-down ## Alias for migrations-down

.PHONY: create test user
create-test-user: ## Create test user
	docker exec -it -u root $(CONTAINER_NAME) php src/Account/Ui/CreateUserCommand.php admin@email.com admin test
c-t-u: create-test-user ## Alias for create-test-user