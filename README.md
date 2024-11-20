<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>


## About This CLI Application

To get started with this application, follow these steps:

1. **Create a `.env` File:**

Copy all the content from the `.env.example` file to create your `.env` file.

```bash
cp .env.example .env
```


2. **Install dependencies:**
   
Install Composer if you haven't already. Then run the following command to install the dependencies:

```bash
#Install dependencies
composer install
```

3. **Start the Application:**

After the installation is complete, you can generate the report by running the following command:

```bash
# Start the application
php artisan app:generate-report
```

4. **Run the Automated Tests:**
To run the tests and ensure everything is working correctly, execute:

```bash

# Run the automated tests
php artisan test
```
