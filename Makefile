ifndef APP_ENV
	include .env
endif

###> symfony/framework-bundle ###
CONSOLE := $(shell which bin/console)
sf_console:
ifndef CONSOLE
	@printf "Run \033[32mcomposer require cli\033[39m to install the Symfony console.\n"
endif

cache-clear:
ifdef CONSOLE
	@$(CONSOLE) cache:clear --no-warmup
else
	@rm -rf var/cache/*
endif
.PHONY: cache-clear

cache-warmup: cache-clear
ifdef CONSOLE
	@$(CONSOLE) cache:warmup
else
	@printf "cannot warmup the cache (needs symfony/console)\n"
endif
.PHONY: cache-warmup

serve_as_sf: sf_console
ifndef CONSOLE
	@${MAKE} serve_as_php
endif
	@$(CONSOLE) | grep server:start > /dev/null || ${MAKE} serve_as_php
	@$(CONSOLE) server:start

	@printf "Quit the server with \033[32;49mbin/console server:stop.\033[39m\n"

serve_as_php:
	@printf "\033[32;49mServer listening on http://127.0.0.1:8000\033[39m\n";
	@printf "Quit the server with CTRL-C.\n"
	@printf "Run \033[32mcomposer require symfony/web-server-bundle\033[39m for a better web server\n"
	php -S 127.0.0.1:8000 -t public

serve:
	@${MAKE} serve_as_sf
.PHONY: sf_console serve serve_as_sf serve_as_php
###< symfony/framework-bundle ###

design:
	npm run watch

dev:
	@${MAKE} serve
	@${MAKE} design

sam:
	@$(CONSOLE) server:run --no-debug &
	@${MAKE} design

reset-rules:
	@$(CONSOLE) doctrine:database:drop --force -vvv
	@$(CONSOLE) doctrine:database:create -vvv
	@$(CONSOLE) doctrine:schema:update --force -vvv
	@$(CONSOLE) fos:user:create admin pierrechanel.gauthier@gmail.com admin --super-admin -vvv
	@$(CONSOLE) rule:import -vvv
