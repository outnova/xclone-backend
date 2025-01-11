# X Clone Backend

This guide outlines the steps to bootstrap the X Clone Backend project.

## Installation

1.  **Install XAMPP:** Download and install XAMPP from the official Apache Friends website ([https://www.apachefriends.org/](https://www.apachefriends.org/)). XAMPP provides a local server environment with Apache, MySQL, and PHP.

2.  **Install Composer:** Download and install Composer, the dependency manager for PHP, from the official website ([https://getcomposer.org/](https://getcomposer.org/)).

3.  **Clone the project (if applicable):** If you're starting from an existing repository, clone it using Git:

    ```bash
    git clone <repository_url>
    ```

4.  **Install project dependencies:** Navigate to your project directory in the terminal and run:

    ```bash
    composer install
    ```

    This command installs all the necessary PHP packages defined in the `composer.json` file.

## Configuration

1.  **Create `.env` file:** Copy the `.env.example` file to create a new `.env` file:

    ```bash
    cp .env.example .env
    ```

2.  **Configure database connection:** Open the `.env` file and update the database configuration properties to match your local environment:

    ```
    DB_CONNECTION=pgsql # or mysql, sqlite, etc.
    DB_HOST=127.0.0.1
    DB_PORT=5432 # or 3306 for MySQL
    DB_DATABASE=your_database_name
    DB_USERNAME=your_database_user
    DB_PASSWORD=your_database_password
    ```

3.  **Enable PHP extensions:** Ensure the following PHP extensions are enabled in your `php.ini` file. This file is usually located in the `php` directory within your XAMPP installation (e.g., `C:\xampp\php\php.ini`). Uncomment (remove the semicolon `;`) the following lines:

    ```ini
    extension=pdo_pgsql
    extension=pgsql
    extension=zip
    ```

    After making changes to the `php.ini` file, restart your Apache server.

## Running the Application

1.  **Start the development server:** In your project directory, run the following command:

    ```bash
    php artisan serve
    ```

    This will start the Laravel development server. You can access your application in your web browser at the address displayed in the terminal (usually `http://127.0.0.1:8000`).

## Troubleshooting

*   **Composer errors:** If you encounter errors during `composer install`, ensure you have the correct PHP version installed and that Composer is configured correctly.
*   **Database connection errors:** Double-check the database credentials in your `.env` file and ensure your database server is running.
*   **Missing PHP extensions:** If you encounter errors related to missing PHP extensions, verify that they are enabled in your `php.ini` file and that you have restarted your Apache server.

This guide provides a basic setup for a Laravel project. Further configurations might be needed based on your project's specific requirements.