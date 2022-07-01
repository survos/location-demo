web:  vendor/bin/heroku-php-nginx -C heroku-nginx.conf  -F fpm_custom.conf public/
release: bin/console doctrine:migrations:migrate -n --allow-no-migration
