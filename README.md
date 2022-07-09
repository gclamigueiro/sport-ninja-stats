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
- Library ```pcntl``` installed in php. Required by Laravel Horizon.
- Add volume to mysql, to persist data

### Containers deployed and ports
- nginx - :8080
- mysql - :3306
- php - :9000
- redis - :6379
- phpmyadmin -: 9090

## To run the application
- Rename ```.env.example``` to ```.env``` in src folder.
- Run docker compose up command with the command below  
    ```docker-compose --env-file ./src/.env up -d --build site```
- Run migrations  
    ```docker-compose run --rm artisan migrate```

## To stop the application
- Execute docker compose down command  
    ```docker-compose down```

## To Test the application

## Use Laravel Horizon 

 Laravel Horizon allows to monitor key metrics of your queue system such as job throughput, runtime, and job failures. (Horizon Documentation)<https://laravel.com/docs/8.x/horizon> 

### Execute Horizon

```docker-compose run --rm artisan horizon```

Url <http://localhost:8080/horizon/>


## Usefuel commands

### Run artisan commands

```docker-compose run --rm artisan <command>```   

examples:  

- ```docker-compose run --rm artisan make:job ProcessInvalidateCache```
- ```docker-compose run --rm artisan queue:work```
- ```docker-compose run --rm artisan config:clear```

### Run composer commands
```docker-compose run --rm composer make:resource <command>```
example:
- ```docker-compose run --rm composer require predis/predis```
- ```docker-compose run --rm composer dump-autoload```

# Use the frontend to test the endpoints

In case the page does not load correctly, it would be neccesary to compile the js assets again. For this, run the following commands:

```docker-compose run --rm npm install```
```docker-compose run --rm npm run dev```

Enter to <http://localhost:8080/>

## See Redis keys
- Enter into the redis container
- Execute ```redis-cli``` and ```KEYS *```