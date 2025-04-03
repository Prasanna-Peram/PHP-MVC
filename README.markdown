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

1. Introduced a new "Author" field in the user interface to allow inserting new Posts - see `app/schema.php` - this file is auto-migrated by the application.
2. CRUD Operations: Fully implemented Create, Read, Update, and Delete operations for posts.
3. Implemented Validations:
		Field-level validations on the UI.
		Backend validations.
		Prevented duplicate post entries.
4. Added a confirmation dialog before deleting a post.
5. Implemented test cases.
6. Introduced metadata columns (created_at, updated_at).
7. Implemented API end points for Add, Update and Delete.
8. UI Improvements:
		Displayed posts in a nicely formatted table.
		Added search functionality for posts.
		Added view icon in the grid that displays data in a pop-up.
9.  Ensured [PHPStan](https://phpstan.org/) passes.
10. Ensured [PHPUnit](https://phpunit.de/index.html) passes.

## Future Enhancements

1.	Refactor Post Model:
		Define all post-related variables inside the Post model.
		Move common properties (e.g., id) to a parent model.
2.	User Authentication:
		Implement login/logout functionality for security.
		Allow only authorized users to create, edit, and delete posts.
3.	UI Enhancements:
		Implement a sidebar menu for post management.
		Improve frontend component structure by splitting large components.
4.	Optimize Asset Management:
		Replace CDN links with locally hosted assets.
5.	Frontend Routing:
		Implement client-side navigation.
6.	Post Enhancements:
		Add comment support (one-to-many relationship).
		Track and display the number of views per post.
7.	User & Group Management:
		Implement user roles and permissions.
		Send notifications for new posts to admin users.
8.	Admin Reports:
		Provide statistics and analytics on posts.
9.	State Management:
		Introduce state management for better UI flow handling, this can be for larger application.
10.	Soft Delete:
		Implement soft deletion to allow recovery of deleted posts.
	
## Directory Structure

* `/app` - application business-logic
* `/src` - lightweight MVC framework, for example only
* `/public/index.php` - front-controller for intercepting requests
