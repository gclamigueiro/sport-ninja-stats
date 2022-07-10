# SportNinja Stats
## Technologies
- Laravel 8
- Mariadb
- Docker Compose 
- Redis (For queueing and caching)
- Horizon (To check redis queue)
- React (To create a page to test the endpoints)

## Requirements
- Docker
- Docker Compose

## To run the application
- Rename ```.env.example``` to ```.env``` in the src folder.
- Run docker compose up command with the command below:  
    ```docker-compose --env-file ./src/.env up -d --build site```
- Install dependencies:    
    ```docker-compose run --rm composer update```    
- Run migrations:    
    ```docker-compose run --rm artisan migrate --seed```
- Run queue to process jobs:    
    ```docker-compose run --rm artisan queue:work``` 
    
## To stop the application
- Execute docker compose down command: 
    ```docker-compose down```
    
## Test the application
- You can use the following Curls:
  - Insert Stats ->  [Download Curl](./.readme-resources/curl-insert-stats.txt)
  - Get Stats ->  [Download Curl](./.readme-resources/curl-get-stats.txt)
- You can go to <http://localhost:8080> and generate and recover stats. 

## Description of the solution
- Models Created
  - Player: Created only to have a better database structure.
  - Stats:  Where the info is stored. Columns: id, player_id, name, value, created_at, updated_at
- Controller
  - PlayerController:
    - Get Method(`/players/stats`): Used to retrieve players stats.
      - StatsCacheMiddleware: Used in get method to save and retrieve from redis cache.
    - Post Method (`/players/stats`): Stores the stats. Uses redis Queue to handle a large amount of petitions and respond faster to the client. Uses the following processes:
      - ProcessStats: It is where stats are saved.
      - ProcessInvalidateCache: A process called at most 2 times a minute when new stats are saved to invalidate the cache data (maybe would be better done after a certain time if new stats were inserted and also dispatch the process after the stats process is finished).
      
## Template used to set up Docker Compose

 <https://github.com/aschmelyun/docker-compose-laravel>

### Updates made over the template
- PhpMyAdmin container added
- Library ```pcntl``` installed in PHP. Required by Laravel Horizon.
- Added volume to MySQL Container, to persist data.

### Containers deployed and ports
- nginx - :8080
- mysql - :3306
- php - :9000
- redis - :6379
- phpmyadmin -: 9090

## Laravel Horizon 
Laravel Horizon allows you to monitor key metrics of your queue system such as job throughput, runtime, and job failures. [Horizon Documentation](https://laravel.com/docs/8.x/horizon)

![Horizon Completed Jobs Example](https://raw.githubusercontent.com/gclamigueiro/sport-ninja-stats/master/.readme-resources/horizon-completed-jobs.PNG)

### Run Horizon
Execute command:  
```docker-compose run --rm artisan horizon```  
Go to the following url:  
<http://localhost:8080/horizon/>

## Useful commands

### Run artisan commands

```docker-compose run --rm artisan <command>```   

examples:  

- ```docker-compose run --rm artisan make:job ProcessInvalidateCache```
- ```docker-compose run --rm artisan queue:restart```
- ```docker-compose run --rm artisan config:clear```

### Run composer commands
```docker-compose run --rm composer <command>```  

example:

- ```docker-compose run --rm composer require predis/predis```
- ```docker-compose run --rm composer dump-autoload```

## Compile Frontend

In case the page does not load correctly, it would be necessary to compile the frontend assets again. For this, run the following commands:

```docker-compose run --rm npm install```  
```docker-compose run --rm npm run dev```

## See Redis keys
- Enter into the redis container
- Execute ```redis-cli``` and ```KEYS *```
