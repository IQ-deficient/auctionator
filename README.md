# Auction House Web Application
Auction (english style) web application serving to improve my knowledge in web development.

## Features
- Laravel 8.x
- Vue 2 + Vuetify
- JSON Web Token

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
- Clone .env.example file into .env and configure: DB_DATABASE, MAIL_USERNAME, MAIL_FROM_ADDRESS
- Email being used needs Two-Factor Authentication and Google 'App Password' as MAIL_PASSWORD
- Make sure MySql database is running
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
- And in another one run Laravel Queue Listener
```bash
cd back
php artisan queue:listen
```

## Running the application interface
- Open a fresh Terminal on project root
```bash
cd front
npm run serve
```
- Open given server IP in browser of choice
