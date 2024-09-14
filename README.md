# Project VeterinaryApplication
## About 
VeterinaryApplication is a web application built with [Laravel](https://laravel.com/), a PHP framework designed for web artisans. 
> [!NOTE]
_**It includes a chat system using Pusher, along with notifications and a shopping cart system. The application is designed for veterinarians and breeders, offering services such as a chat system for group conversations between breeders, as well as private chats between veterinarians and breeders. It also features a shopping cart for purchasing medicines, an order list, and real-time notifications powered by Pusher**_
> 
## Installation
1- Clone the repository
   ```bash
git clone  https://github.com/AYA2300/VeterinaryApplication.git

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
4- Setting Up Pusher and Configure your .env file with your database credentials and other settings (like mail, pusher, etc.)
```
composer require pusher/pusher-php-server
```
### Create a Pusher Account
Go to Pusher and sign up for an account if you don't already have one.
Once signed in, create a new application on your Pusher dashboard. You'll need to provide details such as the application name and the cluster region.
After creating the application, you will be provided with credentials including app_id, key, 
### Update Your .env File

```
PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=eu
BROADCAST_DRIVER=pusher

```
5- Generate a JWT secret key:
```
php artisan jwt:secret
```
6-Migrate the database:
```
php artisan migrate
php artisan db:seed
```
> [!NOTE]
admin account:

 email: admin@gmail.com <br>
 password :12345678

 6-Start the Laravel development server:
 ```
php artisan serve
```
## :bell: Technologies Used
- **Laravel** - A PHP framework for building web applications.
- **JWT (JSON Web Token)** - For handling authentication and securing API routes.
- - **Spatie Packages** - For managing roles, permissions, and other functionalities in a clean and structured way.
  - [spatie/laravel-permission](https://github.com/spatie/laravel-permission) for managing roles and permissions.
-**Pusher** - For real-time communication, such as chats and notifications.
- **Guards** - Used for managing multiple user types (e.g., veterinarians and breeders) and authentication processes.
- **Shopping Cart System** - For managing and processing medicine orders.
- **Order Management** - To handle order lists and statuses.
- **Service Layer Architecture** - Used to cleanly separate business logic from the controllers, ensuring a clean code structure.





