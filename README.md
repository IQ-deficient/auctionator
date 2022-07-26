# Auctionator - Auction House Web Application

#### Full stack web application serving to improve my knowledge in web development.

## Features
- Laravel 8.x
- Vue 2 + Vuetify
- MySQL
- JSON Web Token

## Dependency installation from root
```bash
cd back
composer install
cd..
cd front
npm install
cd..
```

## Setup Frontend
- Clone .env.example as new .env file

## Setup and Run Backend
- Clone .env.example file into .env and configure: DB_DATABASE, MAIL_USERNAME, MAIL_FROM_ADDRESS, CONTACT_US_MAIL
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
- In another Terminal run Laravel Queue Listener
```bash
cd back
php artisan queue:listen
```
- And in another one run Laravel Scheduler
```bash
cd back
php artisan schedule:work
```

## Running the application Client side
- Open a fresh Terminal on project root
```bash
cd front
npm run serve
```
- Open given server IP in browser of choice

## Supplemental Information
- Here is the initial [Database Diagram](https://dbdiagram.io/d/61a28d408c901501c0d56473)

