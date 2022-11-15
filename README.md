## Api Boiler Plate

This is a complete api boiler plate that allows you to easily set up and get your development work going without any hassle. The boiler plate is built on Laravel and has the following implementation out of the box

- User Authentication using laravel passport for token management
- User Registration
- Roles Management
- Permissions Management
- Role Based Access Level Control (This can be modified to permission based for granular control)
- Profile Management


## Installation

Setting up this boiler plate is easy especially for those familiar with the laravel ecosystem. The following steps are required to get this up and running

- Clone the project
- Run `composer install` to install dependency
- Run `composer update` if the lock file does not contain a compatible set of packages.
- Copy the `.env.example` to `.env` file
- Set up the database credentials to suit your configuration
- Run `php artisan migrate` to migrate the tables into your database
- Run `php artisan passport:install` to get your personal access client and grant created
- Run `php artisan db:seed --class=UserTableSeeder;` to seed an initial user to your users table
- Download the postman collection from the link above
- Import the downloaded collection
- Serve the application on any port of your choice

## Todo
- Implement Unit and feature tests on all the functionalities
