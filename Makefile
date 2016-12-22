install: composer.json
	cp .env.example .env
	cp config.yaml.example config.yaml
	composer update

.PHONY: install
