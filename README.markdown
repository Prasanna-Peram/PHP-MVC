# BDT Example App

## Setup

```bash
# Build the Docker image
docker build -t bdt-code-sample .

# Composer install
docker run -v $(pwd):/app -w /app bdt-code-sample composer install

# Run PHPStan
docker run -v $(pwd):/app -w /app bdt-code-sample ./vendor/bin/phpstan

# Run PHPUnit
docker run -v $(pwd):/app -w /app bdt-code-sample ./vendor/bin/phpunit

# Run PHPUnit (with coverage report)
docker run -v $(pwd):/app -w /app bdt-code-sample ./vendor/bin/phpunit --coverage-html coverage

# Run the web server: http://localhost:8000
docker run -v $(pwd):/app -w /app -p 8000:8000 bdt-code-sample
```

## Description

Given the existing structure, please implement the following:

1. Add an Author field to the user interface to allow inserting new Posts - see `app/schema.php` - this file is auto-migrated by the application.
2. Edit an existing post.
3. Delete an existing post.
4. Ensure [PHPStan](https://phpstan.org/) passes.
5. Ensure [PHPUnit](https://phpunit.de/index.html) passes.

The code provided serves as a guidance only, feel free to modify it to
suit your needs.

Keep in mind that we are testing your PHP MVC and OOP knowledge and front-end skills.

**Optional:**
1. Implement input validation where necessary
2. Prevent duplicate post titles

## Directory Structure

* `/app` - application business-logic
* `/src` - lightweight MVC framework, for example only
* `/public/index.php` - front-controller for intercepting requests
