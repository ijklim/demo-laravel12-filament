# Eco-Friendly Rec Center Admin

A management system for a community center dedicated to sustainable, healthy living. This project demonstrates a **Laravel 12** application using **Filament** and the **TALL stack**.

## Concept

Manage community activities focusing on:
* **Pickleball**: Booking courts and preventing conflicts.
* **Line Dancing**: Scheduling classes.
* **Vegetarian Potluck**: Organizing community events.
* **Sustainability**: Tracking "Energy Saved" metrics.

## Features to Showcase

* **Calendar Widget**: A visual calendar on the dashboard to view court bookings and dance class schedules (using `saade/filament-fullcalendar`).
* **Resource Booking**: Smart logic to prevent double-booking of Pickleball courts during the same time slot.
* **Dashboards**: Custom metrics for "Energy Saved" (Environmental impact) and "Active Members".
* **Filtering**: Advanced table filtering to find members by their interests (e.g., "Show me all Line Dancers").

## Tech Stack

* **Framework**: Laravel 12
* **Admin Panel**: FilamentPHP v4
* **Frontend**: Tailwind CSS, Alpine.js, Livewire (TALL Stack)

## Getting Started

Follow these steps to set up and run the project locally.

### Prerequisites

* PHP 8.2+
* Composer
* Node.js & NPM

### Installation

1.  **Clone the repository** (if not already done).

2.  **Install PHP dependencies**:
    ```bash
    composer install
    ```

    **For Production**:
    If deploying to production, skip dev dependencies to improve performance and security.
    ```bash
    composer install --no-dev --optimize-autoloader
    ```
    *Troubleshooting: If you see "Please provide a valid cache path" (In Compiler.php), it means Laravel's storage directories are missing.*
    ```bash
    mkdir -p storage/framework/{cache,sessions,views}
    composer install --no-dev --optimize-autoloader
    ```

3.  **Install Node dependencies**:
    ```bash
    npm install
    ```

4.  **Environment Setup**:
    Copy the `.env.example` file to `.env`:
    ```bash
    cp .env.example .env
    ```
    Generate application key:
    ```bash
    php artisan key:generate
    ```
    Configure your database settings in the `.env` file.

5.  **Database Migration**:
    Run the migrations to set up the database schema:
    ```bash
    php artisan migrate
    ```

6.  **Create Admin User**:
    (Already created in this demo setup)
    * **Email**: `admin@example.com`
    * **Password**: `password`

    If you need to create a new one:
    ```bash
    php artisan make:filament-user
    ```

### Running the Application

1.  **Start the Backend Server**:
    ```bash
    php artisan serve
    ```

2.  **Start the Frontend Asset Server** (for Tailwind/Filament styles):
    ```bash
    npm run dev
    ```

3.  **Access the Admin Panel**:
    Open your browser and navigate to:
    [http://127.0.0.1:8000/admin](http://127.0.0.1:8000/admin)

## Performance Tips

### Cache Blade Icons

The **Blade Icons** package (used by Filament for Heroicons) can significantly slow down your application if icons are not cached. Without caching, it scans the filesystem for SVG files on every request.

**Always cache icons in production:**
```bash
php artisan icons:cache
```

To clear the cache (e.g., after adding new icons):
```bash
php artisan icons:clear
```

> **Tip**: Add `php artisan icons:cache` to your deployment script alongside other optimization commands like `config:cache`, `route:cache`, and `view:cache`.


## Code Overview

### Models
* `Member`: Community members with interests stored as JSON.
* `Court`: Pickleball courts available for booking.
* `CourtBooking`: Reservation records linking Members to Courts.
* `ClassSession`: Scheduled classes like Line Dancing.

### Filament Resources
Located in `app/Filament/Resources/`:
* **MemberResource**: Manages members. Includes a `SelectFilter` to filter the table by specific interests.
* **CourtBookingResource**: Manages bookings. Contains a closure-based validation rule in the form to **prevent double-booking** (overlapping times).

### Widgets
Located in `app/Filament/Widgets/`:
* **CalendarWidget**: Aggregates `CourtBooking` and `ClassSession` events into a single interactive calendar view.
* **DashboardStats**: Displays custom metrics like "Energy Saved" (calculated based on booking usage) and specific valid member counts.
