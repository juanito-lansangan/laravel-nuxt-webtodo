# 01092025-juanito-lansangan-web

## Web TODO App Challenge

Appetiser fullstack coding challenge with Laravel + Vue + Documentation

##Instructions

clone the repo

```
git clone https://gitlab.com/appetiser/appetiser-pre-hire-coding-challenge/01092025-juanito-lansangan-web.git
```

change directory e.g cd <repo directory>

```
cd 01092025-juanito-lansangan-web/
```

copy laravel env file

```
cp src/backend-api/.env.example src/backend-api/.env
```

build the docker images

```
docker-compose build
```

run the docker containers

```
docker-compose up -d && docker ps -a
```

install laravel dependencies

```
docker-compose exec app composer install
```

run the automated tests for laravel api to see if all features are not broken

```
docker-compose exec app php artisan test
```
