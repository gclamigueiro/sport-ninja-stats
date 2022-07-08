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


# Use horizon 

 Horizon allows you to monitor key metrics of your queue system such as job throughput, runtime, and job failures

https://laravel.com/docs/8.x/horizon

docker-compose run --rm artisan horizon 

http://localhost:8080/horizon/

# To Test the application


# to run artisan command

docker-compose run --rm artisan <command>

example:

docker-compose run --rm artisan make:job ProcessStats
docker-compose run --rm artisan queue:work --verbose --tries=3 --timeout=90
docker-compose run --rm artisan config:clear

# to run composer command
docker-compose run --rm composer <command>
example:
docker-compose run --rm composer require predis/predis
docker-compose run --rm composer dump-autoload


# install frontend

docker-compose run --rm npm install
docker-compose run --rm npm run dev

## useful documentation
https://stackoverflow.com/questions/54566977/laravel-horizon-throws-error-call-to-undefined-function-laravel-horizon-consol