# Payment Gateway Integration System

A Laravel-based web application demonstrating secure payment processing using the SSLCommerz payment gateway, along with user authentication, role-based authorization, and social login support.

This project is not a generic payment gateway implementation. It focuses on integrating a real-world payment provider within a structured Laravel application, following best practices for authentication, authorization, and maintainability.

## Features

- SSLCommerz payment gateway integration
- Secure payment request and callback handling
- User authentication using Laravel Fortify
- Role-based authorization using Spatie Permission
- Social login with Google and Facebook
- Environment-based configuration
- Laravel MVC architecture
- Automated testing support

## Tech Stack

### Backend
- PHP >= 8.0
- Laravel Framework

### Authentication & Authorization
- Laravel Fortify
- Spatie Laravel Permission

### Payment Gateway
- SSLCommerz

### Frontend
- Blade Templates
- Vite (Asset Bundling)

### Database
- MySQL (or any Laravel-supported relational database)

### Testing
- PHPUnit

## Project Structure

app/        Application core logic  
bootstrap/ Laravel bootstrap files  
config/     Configuration files  
database/   Migrations and seeders  
public/     Publicly accessible files  
resources/  Views and frontend assets  
routes/     Web and API routes  
storage/    Logs and cached files  
tests/      Automated tests  

## Installation

### Prerequisites
- PHP >= 8.0
- Composer
- MySQL
- Node.js & npm

### Steps

1. Clone the repository:
   git clone https://github.com/sampad-singha/payment_gateway.git

2. Navigate to the project directory:
   cd payment_gateway

3. Install PHP dependencies:
   composer install

4. Install frontend dependencies:
   npm install
   npm run build

5. Copy environment file:
   cp .env.example .env

6. Generate application key:
   php artisan key:generate

## Environment Configuration

Update the following variables in the `.env` file:

DB_DATABASE=your_database_name  
DB_USERNAME=your_database_user  
DB_PASSWORD=your_database_password  

SSLCOMMERZ_STORE_ID=your_store_id  
SSLCOMMERZ_STORE_PASSWORD=your_store_password  
SSLCOMMERZ_SANDBOX=true  

GOOGLE_CLIENT_ID=your_google_client_id  
GOOGLE_CLIENT_SECRET=your_google_client_secret  

FACEBOOK_CLIENT_ID=your_facebook_client_id  
FACEBOOK_CLIENT_SECRET=your_facebook_client_secret  

## Running the Application

Run database migrations:
php artisan migrate

Start the development server:
php artisan serve

The application will be available at:
http://127.0.0.1:8000

## Testing

Run automated tests using PHPUnit:
php artisan test

## License

This project is open-source and available under the MIT License.
