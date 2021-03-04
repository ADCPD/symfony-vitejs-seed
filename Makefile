#-- VARIABLES -------------------------------------
PROJECT = Bazar
AUTHOR = dhaouadi.amir@gmail.com
DOCKER = docker-compose
EXEC_PHP = php

#-- DOCKER -------------------------------------
bazar-start: ## start docker project
	sudo $(DOCKER) up -d

bazar-stop: ## start docker project
	sudo $(DOCKER) stop

bazar-shell: ## start docker php container
	sudo $(DOCKER) exec $(EXEC_PHP) bash

bazar-install: ## start docker php && install vendors
	sudo $(DOCKER) run php composer install

#-- SYMFONY -------------------------------------
fix-permis-public: ## Give 0777 ROLES to public for folder
	chmod -R 0777 public/*

jwt-private: ## Generate a jwt private key
	openssl genrsa -out $(JWT_PATH)/private.pem -aes256 4096

jwt-public: ## Generate a jwt private key
	openssl rsa -pubout -in $(JWT_PATH)/private.pem -out $(JWT_PATH)/public.pem