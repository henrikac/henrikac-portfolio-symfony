# HenrikAC Symfony

## Requirements
- [PHP 8.1+](https://www.php.net/)
- [Docker](https://www.docker.com/) *(or PostgreSQL)*

## Setup
Run `cp .env .env.local` and update `.env.local`.

#### Server
```
$ composer install
```

Make sure the database is created and running before running the following command

```
$ ./bin/console doctrine:migrations:migrate
```

#### Assets
This project uses Symfony AssetMapper and `symfonycasts/tailwind-bundle`.

```
$ php bin/console tailwind:build
```

For local development with automatic Tailwind rebuilds:

```
$ php bin/console tailwind:build --watch
```

For production builds:

```
$ php bin/console tailwind:build --minify
$ php bin/console asset-map:compile
```

## Usage
Run `symfony serve -d` and then navigate to `localhost:8000`.  

The admin section can be found on `/admin`.

#### Fetch github repository data
To fetch and save your GitHub repository data to your database run the following command

```
$ ./bin/console app:fetch:repositories
```

*Tip:* Have a cron job run `php bin/console app:fetch:repositories` e.g. every 5 minutes to keep the data shown on the portfolio data up-to-date.  

To set up a cron job that runs every 5 minutes simply run `crontab -e` and then add a line at the bottom of the file that opens

```
*/5 * * * * /usr/bin/php /path/to/your/project/bin/console app:fetch:repositories
```

This cron job should now run every 5 minutes.

#### Create superuser
To create a superuser that is able to log in to the admin section simply run

```
$ ./bin/console app:create:superuser
```

and enter an email and a password.
