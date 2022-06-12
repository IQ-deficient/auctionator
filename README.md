# Auction House Web Application
Auction (english style) web app formerly made to satisfy the bare minimum required to get my bachelors degree, currently serving to improve my knowledge in web development.

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
- Clone .env.example file into .env and configure: DB_DATABASE, MAIL_USERNAME, MAIL_PASSWORD, MAIL_FROM_ADDRESS
```bash
cd back
php artisan jwt:secret
php artisan key:generate
php artisan storage:link
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
- Open a fresh Terminal on project root
```bash
cd front
npm run serve
```
- Open given server IP in browser of choice
