# Ninja Sport Stats


# Template utilized to set up the project with docker composer
https://github.com/aschmelyun/docker-compose-laravel

# Containers used

nginx - :8080
mysql - :3306
php - :9000
redis - :6379
phpmyadmin -: 9090

# To run the app
docker-compose --env-file ./src/.env up -d --build site

docker-compose run --rm artisan migrate

# To stop the app

docker-compose down 

# To Test the application


# to run artisan command

docker-compose run --rm artisan <command>

example:

docker-compose run --rm artisan make:job ProcessStats
docker-compose run --rm artisan queue:work redis
docker-compose run --rm artisan config:clear

# to run composer command
docker-compose run --rm composer <command>
example:
docker-compose run --rm composer require predis/predis



