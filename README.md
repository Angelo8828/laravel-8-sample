### About
Sample project written on Laravel 8 with sample event handling, automated tests and API returns. Please see `tests` directory to check how the app works.

### Prerequisites
* Docker
* If Docker is not available, the application will run on:
    * Nginx
    * PHP 8.0
    * MySQL 5.7

### Steps to run

1. Copy the contents of `.env.example` and `.env.testing.example` to create your `.env` and `.env.testing` config files.

2. Run `docker-compose up -d` to build the containers.

3. Please visit http://localhost:8001 to access the application and http://localhost:8081 to access PHPMyAdmin

4. Please run migrations and database seeders using commands `docker exec -it app php artisan migrate` and `docker exec -it app php artisan db:seed`.

5. Create `test_phpunit` database (can be easily done on PHPMyAdmin) to perform tests

6. Run `docker exec -it app php artisan test
` to perform initial tests and see if the app runs as expected.

Notes: Generate new app key by running `docker exec -it app php artisan key:generate` if needed after running all these steps.
