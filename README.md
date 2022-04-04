# Auction House Web Application
Real estate auctioning app made to satisfy the bare minimum required to get my bachelors degree.

## Features
- Laravel 8.x
- Vue 2 + Vuetify

## Installation from root
```bash
cd back
composer install
cd..
cd front
npm install
cd..
```

## Setup
- Make sure Apache & MySql are running (XAMPP)
- Configure .env file DB_DATABASE variable as your local mysql database
```bash
cd back
php artisan jwt:secret
php artisan migrate:fresh --seed
```
- Serve the application in Terminal
```bash
php artisan serve
```
- In another Terminal run the Laravel Scheduler
```bash
cd back
php artisan schedule:work
```

## Running the application interface
- Open a fresh Terminal in project root
```bash
cd front
npm run serve
>click
```

