![Suggestive Logo](suggestive-logo.png)

[ ![Codeship Status for mattstauffer/suggestive](https://codeship.com/projects/13c6db40-7e06-0133-5999-1a60f99c26b7/status?branch=master)](https://codeship.com/projects/120269)

Suggestive makes it easy for podcasts and other recurring content sources to take suggestions from fans/followers. **Suggestive is still under development. There are no active releases yet. It's pre-Alpha, so fork and use at your own discretion.**

**Features:**

[Features.md](https://github.com/mattstauffer/suggestive/blob/master/features.md)

Demo of current progress:

![Demo of where it is right now](demo.gif)

### Requirements

* PHP >= 5.5.9
* PHP [mcrypt extension](http://php.net/manual/en/book.mcrypt.php)
* A [supported relational database](http://laravel.com/docs/5.1/database#introduction) and corresponding PHP extension
* [Composer](https://getcomposer.org/download/)

### Installation

1. (Optionally) [Fork this repository](https://help.github.com/articles/fork-a-repo/)
2. Clone the repository locally
3. [Install dependencies](https://getcomposer.org/doc/01-basic-usage.md#installing-dependencies) with `composer install`
4. Copy [`.env.example`](https://github.com/mattstauffer/suggestive/blob/master/.env.example) to `.env` and modify its contents to reflect your local environment.
5. [Run database migrations](http://laravel.com/docs/5.1/migrations#running-migrations). If you want to include seed data, add a `--seed` flag.

    ```bash
    php artisan migrate --env=local
    ```
6. Configure a web server, such as the [built-in PHP web server](http://php.net/manual/en/features.commandline.webserver.php), to use the `public` directory as the document root.

    ```bash
    php -S localhost:8080 -t public
    ```
7. Run tests with `./vendor/bin/phpunit`.
