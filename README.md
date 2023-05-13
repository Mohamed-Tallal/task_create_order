
## About Project

This is a sample project that allows users to create orders. The project uses Laravel Sanctum for authentication, and includes unit tests to ensure that the application functions as expected.

## About Project
To get started with this project, you will need to do the following:

- Clone this repository to your local machine.
- Install the necessary dependencies using Composer.
- Create a copy of the .env.example file and rename it to .env, then update the database configuration settings to match your environment.
- Run the database migrations using the php artisan migrate command.
- Run the database seeder using the php artisan db:seed command to add fake data to the database.
- Start the server using the php artisan serve command.

##APIs

This application has two APIs:

- Login API: http://localhost:8000/api/login
 This API allows users to log in to the application using Laravel Sanctum. Users must provide their email address and password as part of the request. If the login is successful, the API will return an authentication token that can be used for subsequent requests.

- Create Order API: http://localhost:8000/api/orders
 This API allows authenticated users to create orders for products. Users must provide a list of products and their quantities as part of the request. If the order is successful, the API will return a JSON response containing details about the order

## Running the Tests

To run the unit tests for this project, you can use the php artisan test command.

## Built With

- Laravel 10.
- MySQL.
- Laravel Sanctum.
- PHPUnit.
- Docker.
