# Ninja Sport Stats


# Template utilized to set up the project with docker composer
https://github.com/aschmelyun/docker-compose-laravel

# Containers used

nginx - :8080
mysql - :3306
php - :9000
redis - :6379
phpmyadmin -: 9090

# To install de app
docker-compose --env-file ./src/.env up -d --build site
docker-compose run --rm artisan migrate

# To stop the app

docker-compose down 

# To Test the application