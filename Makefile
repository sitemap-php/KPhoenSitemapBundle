tests: phpunit

phpunit:
	./vendor/bin/phpunit

rusty:
	php ./vendor/bin/rusty check --bootstrap-file=./vendor/autoload.php .
