# Airbnb Explorer

This is a Laravel application for exploring Airbnb listings.

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/MarkColeMukisa/airbnb-explorer.git
   cd airbnb-explorer
   ```

2. Install PHP dependencies:
   ```bash
   composer install
   ```

3. Copy the `.env.example` file and create your environment file:
   ```bash
   cp .env.example .env
   ```

4. Generate an application key:
   ```bash
   php artisan key:generate
   ```

5. Configure your database in the `.env` file.

6. Run database migrations:
   ```bash
   php artisan migrate
   ```

7. Install Node.js dependencies and compile assets:
   ```bash
   npm install
   npm run dev
   ```

8. Start the local development server:
   ```bash
   php artisan serve
   ```

## Usage

Once the application is running, open your web browser and navigate to the address provided by `php artisan serve` (usually `http://127.0.0.1:8000`).

## Contributing

Feel free to contribute to this project. Please follow the existing coding style and submit pull requests.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).