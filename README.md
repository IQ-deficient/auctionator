# Auction House Web Application
Real estate auctioning app made to satisfy the bare minimum required to get my bachelors degree.

## Features
- Laravel 8.x
- Vue 2
- Vuetify

## Installation from root
```bash
cd back
composer install
cd..
cd front
npm install
```

## Setup
- Make sure Apache & MySql are running
- Configure .env file DB_DATABASE variable as your local mysql database
- Run Database Migrations and Seeders
```bash
cd back
php artisan migrate:fresh --seed
```
- Serve the application in terminal
```bash
php artisan serve
```

## Running the application interface
```bash
cd front
npm run serve
```

