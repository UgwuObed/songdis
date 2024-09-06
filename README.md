Laravel Project Setup with RabbitMQ for SMS Sending

This guide will walk you through setting up the Laravel project, configuring the environment for RabbitMQ, and how to authenticate and interact with the API endpoint for sending SMS messages.

Setting Up the Laravel Project

Clone the repository and install dependencies
Make sure you have Laravel and Composer installed. After cloning the project, navigate to the project directory and run:


Step 2: Set up environment variables
Copy the `.env.example` file to `.env`:

cp .env.example .env

Now, configure your environment variables in the `.env` file. You can use the following as a guide:

APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:OlflmWhalmft2q3R+wGR7txDCawd9FGonL73eOVD8Qs=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sms
DB_USERNAME=root
DB_PASSWORD=Ikechukwu@1

QUEUE_CONNECTION=rabbitmq

RABBITMQ_HOST=127.0.0.1
RABBITMQ_PORT=5672
RABBITMQ_USER=guest
RABBITMQ_PASSWORD=guest
RABBITMQ_QUEUE=contact_queue
```

Step 3: Generate application key
Run the following command to generate an application key:


php artisan key:generate

Step 4: Run migrations and seed the database
To set up your database, run the migrations and seed the contacts table with sample data:

php artisan migrate --seed


2. Configuring RabbitMQ

RabbitMQ is used to queue the contact messages that will be sent. Make sure RabbitMQ is installed and running on your system.

Step 1: Install RabbitMQ Server
Follow the [RabbitMQ installation guide](https://www.rabbitmq.com/download.html) to install RabbitMQ for your OS.

Step 2: Start RabbitMQ
Make sure RabbitMQ is running. You can start the service using the following commands:

- On Windows:

rabbitmq-service start

- On Linux:

sudo systemctl start rabbitmq-server

You can confirm it's running by checking the RabbitMQ status:

rabbitmqctl status

Step 3: Install Laravel Queue RabbitMQ Driver
Run the following command to install the RabbitMQ driver for Laravel:

composer require vladimir-yuldashev/laravel-queue-rabbitmq


3. Queue Configuration

Make sure you have set the queue driver to RabbitMQ in your `.env` file:

QUEUE_CONNECTION=rabbitmq

In your `config/queue.php` file, ensure the `rabbitmq` connection is configured:

'rabbitmq' => [
    'driver' => 'rabbitmq',
    'host' => env('RABBITMQ_HOST', '127.0.0.1'),
    'port' => env('RABBITMQ_PORT', 5672),
    'user' => env('RABBITMQ_USER', 'guest'),
    'password' => env('RABBITMQ_PASSWORD', 'guest'),
    'queue' => env('RABBITMQ_QUEUE', 'contact_queue'),
],



4. Authenticating with the API

The project uses **Laravel Sanctum** for API authentication. To interact with the protected endpoints, follow these steps:

Step 1: Register a new user
Make a POST request to the `/api/register` endpoint:


POST http://localhost/api/register
Content-Type: application/json

{
  "name": "John Doe",
  "email": "johndoe@example.com",
  "password": "password123"
}


The response will include a token, which you need for authentication.

Step 2: Log in (if needed)
You can log in using the `/api/login` endpoint:

POST http://localhost/api/login
Content-Type: application/json

{
  "email": "johndoe@example.com",
  "password": "password123"
}


You'll receive a token in the response.

Step 3: Use the token to authenticate
For any protected route (like `/api/send-sms`), include the token in the `Authorization` header:


Authorization: Bearer {your-token-here}


5. Calling the `/send-sms` Endpoint

Once you are authenticated, you can send the contacts to the RabbitMQ queue by calling the `/api/send-sms` endpoint:


POST http://localhost/api/send-sms
Authorization: Bearer {your-token-here}


Expected Response:

{
  "message": "Contacts sent to queue successfully!"
}


6. Running the Queue Worker

To process the jobs in the queue, run the following command in your terminal:

php artisan queue:work


This will listen to the RabbitMQ queue and process the contacts as jobs are dispatched.

  
- Debugging: If any issues arise, check the Laravel logs in `storage/logs/laravel.log` and RabbitMQ logs to diagnose problems.


This concludes the setup and API usage guide for the Laravel project with RabbitMQ integration for SMS sending.

