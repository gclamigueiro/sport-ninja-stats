# Ninja Sport Stats
## Tools utilizad
- Laravel 8
- Mariadb
- Docker Compose 
- Redis (For queueing and caching)
- Horizon (To check redis queue)
- React (To create a page to test the endpoints)
## Requirments
- Docker
- Docker Compose
## To run the application
- Rename ```.env.example``` to ```.env``` in src folder.
- Run docker compose up command with the command below:
    ```docker-compose --env-file ./src/.env up -d --build site```
- Run migrations:  
    ```docker-compose run --rm artisan migrate --seed```
## To stop the application
- Execute docker compose down command: 
    ```docker-compose down```
## Test the application
- You can use the following Curls:
  - Insert Stats ->  [Download Curl](./.readme-resources/curl-insert-stats.txt)
  - Get Stats ->  [Download Curl](./.readme-resources/curl-get-stats.txt)
- You can enter to `http://localhost:8080` and generate and recover stats. 
  ## Description of the solution
- Models Created
  - Player: Created only to have a better database structure.
  - Stats: Where the info is stored. Columns: `id,player_id,name,value, created_at, updated_at`
- Controller
  - PlayerController:
    - Get Method(`/players/stats`): Use to retrieve players stats.
      - StatsCacheMiddleware: Use in get method to save an retrieve from redis cache.
    - Post Method (`/players/stats`): Store the stats. Use redis Queue to handle a large amount of petitions and repond faster to the client. Use the following processes:
      - ProcessStats: It is where stats are saved.
      - ProcessInvalidateCache: A proccess called at most 2 times a minute when new stats are saved to invalidate the cache data (maybe would be better doing after a certain time if new stats were inserted and also dispatch the proccess after the stats proccess is finished).
## Template utilized to set up docker composer

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
## Use Laravel Horizon 

 Laravel Horizon allows to monitor key metrics of your queue system such as job throughput, runtime, and job failures. [Horizon Documentation](https://laravel.com/docs/8.x/horizon)

<center>
<img
 alt="Horizon Completed Jobs"
 src="./.readme-resources/horizon-completed-jobs.png" width="300" height="auto">
</center>

### Run Horizon
Execute command:  
```docker-compose run --rm artisan horizon```
Go to the following url:  
<http://localhost:8080/horizon/>


## Usefuel commands

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

In case the page does not load correctly, it would be neccesary to compile the frontend assets again. For this, run the following commands:

```docker-compose run --rm npm install```  
```docker-compose run --rm npm run dev```

## See Redis keys
- Enter into the redis container
- Execute ```redis-cli``` and ```KEYS *```