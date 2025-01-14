# 01092025-juanito-lansangan-web

## Web TODO App Challenge

Appetiser fullstack coding challenge with Laravel + Vue + Documentation

## Prerequisites

Before you begin, ensure you have the following installed on your system:

- [Docker](https://www.docker.com/)
- [Docker Compose](https://docs.docker.com/compose/)

## Instructions

Clone the repo:

```bash
git clone git@github.com:juanito-lansangan/laravel-nuxt-webtodo.git
```

Change directory:

```bash
cd laravel-nuxt-webtodo
```

Copy Laravel `.env` file:

```bash
cp src/backend-api/.env.example src/backend-api/.env
```

Build the Docker images:

```bash
docker-compose build
```

Run the Docker containers:

```bash
docker-compose up -d && docker ps -a
```

Install Laravel dependencies:

```bash
docker-compose exec app composer install
```

Run the automated tests for the Laravel API to ensure all features are functioning correctly:

```bash
docker-compose exec app php artisan test
```

Run migrations and seeders:

```bash
docker-compose exec app php artisan migrate:fresh --seed
```

## Visit Backend API Docs

[http://localhost:8006/docs/api#/](http://localhost:8006/docs/api#/)

Backend setup is complete.

## Install the Frontend

Change to the frontend directory:

```bash
cd src/frontend
```

Install dependencies:

```bash
npm install
```

Run the frontend app:

```bash
npm run dev
```

Visit the app:

[http://localhost:3000](http://localhost:3000)

If all the steps above are followed correctly, you should have dummy data ready. From here, you can click the login button, which will redirect you to the home page.

## Demo

You can use this demo link to test the application: [https://todo.stjudeappraisal.io](https://todo.stjudeappraisal.io)

You can use the following existing account or sign up on the app:

```plaintext
Email: johndoe@example.com
Password: Secret123!
