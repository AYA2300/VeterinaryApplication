# Project VeterinaryApplication
## About 
VeterinaryApplication is a web application built with [Laravel](https://laravel.com/), a PHP framework designed for web artisans. 
> [!NOTE]
It includes a chat system using Pusher, along with notifications and a shopping cart system.
## Installation
1- Clone the repository
   ```bash
git clone https://github.com/AYA2300/VeterinaryApplication
```
2- install PHP dependencies using Composer:
```
composer install
```
3- Environment Setup
```
cp .env.example .env
php artisan key:generate

```
4- Configure your .env file with your database credentials and other settings (like mail, pusher, etc.)
```
PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=eu
BROADCAST_DRIVER=pusher

```


