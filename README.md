# EPS API Training Environment

This Laravel project has been set up for the "EPS Backend Web" training. It includes the code samples and structure referenced in the training materials.

## Prerequisites & Installation

Before setting up the project, ensure you have the following installed:

### 1. PHP
Laravel requires PHP (Version 8.1 or higher is recommended for this project).
- **Download**: [php.net/downloads](https://www.php.net/downloads)
- **Installation Guide**: [php.net/manual/en/install.php](https://www.php.net/manual/en/install.php)
- **Windows Tip**: Consider using [Laragon](https://laragon.org/) or [XAMPP](https://www.apachefriends.org/) for an easier setup.

### 2. Composer
Composer is the dependency manager for PHP.
- **Download**: [getcomposer.org/download](https://getcomposer.org/download/)
- **Documentation**: [getcomposer.org/doc](https://getcomposer.org/doc/00-intro.md)

### 3. Laravel
Once PHP and Composer are installed, you can install Laravel globally or create projects directly.
- **Documentation**: [laravel.com/docs/installation](https://laravel.com/docs/installation)
- **Global Installer** (Optional):
  ```bash
  composer global require laravel/installer
  ```

## Setup

1.  Run `composer install` to ensure all dependencies are installed.
2.  Copy `.env.example` to `.env` and configure your database (e.g., MySQL or SQLite).
3.  Run `php artisan migrate` to create the database tables (ensure database is configured first).
4.  Run `php artisan key:generate` to generate the application key.
5.  Run `php artisan jwt:secret` if using JWT (though Sanctum is set up by default).

## Code Samples Implemented

The following components from the training presentation have been implemented:

### Models (`app/Models`)
-   **Course**: Complete model with relationships (`category`, `sessions`, `agendas`, `notes`), auditing, media library, scopes, and complex business logic (`getEligibility`).
-   **CourseCategory**: Model for course categories.
-   **AuditLog**: Polymorphic model for auditing.
-   **CourseSession**, **CourseAgenda**, **CourseNote**, **CourseCalendar**, **CourseCalendarParticipant**: Placeholder models to support relationships.

### Controllers (`app/Http/Controllers/Api`)
-   **CourseCategoryController**: API controller with `store` method using validation and resources.
-   **AuthController**: Authentication controller with `login` (Sanctum token generation) and `logout`.

### Services (`app/Services`)
-   **CourseParticipantService**: Service layer example for complex registration logic with transactions.

### Observers (`app/Observers`)
-   **CourseObserver**: Observer for `Course` model events (auto-generating code on creation).

### Resources (`app/Http/Resources`)
-   **CourseCategoryResource**: API resource for formatting course category responses.

### Requests (`app/Http/Requests`)
-   **StoreCategoryRequest**: Validation for creating course categories.
-   **LoginRequest**: Validation for login.

### Routes (`routes/api.php`)
-   Authentication routes (`/login`, `/logout`, `/user`).
-   Course Categories resource routes (`/course-categories`).

## Dependencies
-   `owen-it/laravel-auditing`: For auditing changes.
-   `spatie/laravel-medialibrary`: For file/media handling.
