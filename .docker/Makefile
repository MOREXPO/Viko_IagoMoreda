# Executables (local)
DOCKER_COMP = docker compose

# Docker containers
PHP_CONT      = $(DOCKER_COMP) exec php
DATABASE_CONT = $(DOCKER_COMP) exec mariadb
NODE_CONT = $(DOCKER_COMP) exec node

# Executables
PHP      = $(PHP_CONT) php
COMPOSER = $(PHP_CONT) composer
SYMFONY  = $(PHP) bin/console

# Misc
.DEFAULT_GOAL = help
.PHONY        : help build up start down logs sh composer vendor sf cc

## —— 🎵 🐳 The Symfony Docker Makefile 🐳 🎵 ——————————————————————————————————
help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z0-9\./_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

## —— Docker 🐳 ————————————————————————————————————————————————————————————————
up: ## Start the docker hub in detached mode (no logs)
	@$(DOCKER_COMP) up --detach --wait

deploy_frontend: 
	@$(DOCKER_COMP) exec node npm run build-only
	
deploy_backend: 
	@$(DOCKER_COMP) exec php composer dump-env prod 

down: ## Stop the docker hub
	@$(DOCKER_COMP) down --remove-orphans

logs: ## Show live logs
	@$(DOCKER_COMP) logs --tail=0 --follow

sh: ## Connect to the PHP FPM container
	@$(PHP_CONT) sh

database: ## Connect to the database container
	@$(DATABASE_CONT) sh

## —— Composer 🧙 ——————————————————————————————————————————————————————————————
composer: ## Run composer, pass the parameter "c=" to run a given command, example: make composer c='req symfony/orm-pack'
	@$(eval c ?=)
	@$(COMPOSER) $(c)

vendor: ## Install vendors according to the current composer.lock file
vendor: c=install --prefer-dist --no-dev --no-progress --no-scripts --no-interaction
vendor: composer

## —— Symfony 🎵 ———————————————————————————————————————————————————————————————
sf: ## List all Symfony commands or pass the parameter "c=" to run a given command, example: make sf c=about
	@$(eval c ?=)
	@$(SYMFONY) $(c)

cc: c=c:c ## Clear the cache
cc: sf
## —— node 	———————————————————————————————————————————————————————————————
client-dev: 
	$(NODE_CONT) npm run dev -- --host
client-install-packages: 
	$(NODE_CONT) npm install
backend-install-packages: 
	$(PHP_CONT) composer install
deploy-dev: up backend-install-packages client-install-packages client-dev
deploy: up deploy_backend deploy_frontend