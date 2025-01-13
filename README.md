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

run migration and seeders

```
docker-compose exec app php artisan migrate:fresh --seed
```

Visit Backend API Docs
http://localhost:8006/docs/api#/

Backend is done.

##Let's install frontend now

change to frontent directory

```
cd src/frontend
```

install dependencies

```
npm install
```

Run the frontend app

```
npm run dev
```

Visit the app
http://localhost:3000

and that's it, if we run all the steps above we have dummy data ready.
From here you can click the login button and it will redirect you too the home page.

And that's pretty much it.
