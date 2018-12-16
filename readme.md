<p align="center"><img src="https://laravel.com/assets/img/components/logo-laravel.svg"></p>

## About CSV Parser

CSV Parser handles the upload the upload of a CSV file, parsing and validating the file, and displaying results to the user. It is built with the following components:

- Laravel 5.7
- Bootstrap 4
- A little React sprinkled in
- Broadcasting, via Pusher
- Job queue to handle potentially massive files
- PHPUnit for feature level testing

## Requirements

- PHP 7.1+
- Composer
- Pusher account (API keys)
- npm/node

## Setup Process

- `git clone https://github.com/roerjo/csv-parser.git`
- Run `composer install`
- Generate `APP_KEY` via `php artisan generate:key`
- Setup the `.env` file
- Run `npm install`
- Run `npm run dev`
- Check permissions on `storage/logs`

After the able is fininshed, the applications should be accessible via a local server which can be started via `php artisan serve`. If wanting to utilize the database table based queue, see below.

## Queue Setup (database queue)

- Create the database
- Run `php artisan queue:table` and `php artisan queue:failed_table`
- Run `php artisan migrate`
- Change `QUEUE_CONNECTION` to `database` in `.env` file
- Run `php artisan queue:work` to handle processing the stored jobs.
