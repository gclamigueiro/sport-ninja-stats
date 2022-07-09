# Ninja Sport Stats

## Tech utilizad
- Laravel 8
- Mariadb
- Docker Compose 
- Redis (For queueing and caching)
- Horizon (To check redis queue)
- React (To create a page to test the endpoints)

## Initial template utilized to set up docker composer

 <https://github.com/aschmelyun/docker-compose-laravel>

### Updates made over the template
- PhpMyAdmin container added
- Library ```pcntl``` installed in php. Required by Horizon
- Add volumen to mysql, to persist data

### Containers deployed and ports
- nginx - :8080
- mysql - :3306
- php - :9000
- redis - :6379
- phpmyadmin -: 9090

## To run the application
- Rename ```.env.example``` to ```.env``` in src.
- Run docker compose Up command with the command below  
    ```docker-compose --env-file ./src/.env up -d --build site```
- Run migrations  
    ```docker-compose run --rm artisan migrate```


## To stop the application
- Execute docker compose down command  
    ```docker-compose down```

## To Test the application

## Use Laravel Horizon 

 Horizon allows you to monitor key metrics of your queue system such as job throughput, runtime, and job failures

https://laravel.com/docs/8.x/horizon

docker-compose run --rm artisan horizon 

http://localhost:8080/horizon/


# to run artisan command

docker-compose run --rm artisan <command>
example:

docker-compose run --rm artisan make:job ProcessStats
docker-compose run --rm artisan queue:work --verbose --tries=3 --timeout=90
docker-compose run --rm artisan config:clear

# to run composer command
docker-compose run --rm composer make:resource UserCollection
example:
docker-compose run --rm composer require predis/predis
docker-compose run --rm composer dump-autoload


# install frontend

docker-compose run --rm npm install
docker-compose run --rm npm run dev

## useful documentation
https://stackoverflow.com/questions/54566977/laravel-horizon-throws-error-call-to-undefined-function-laravel-horizon-consol

## See Redis keys
- Enter into the redis container
- Execute ```redis-cli``` and ```KEYS *```