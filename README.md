# HenrikAC Symfony

## Requirements
- [PHP 8.1+](https://www.php.net/)
- [Node.js](https://nodejs.org/en/)
- [Docker](https://www.docker.com/) *(or PostgreSQL)*

## Setup
Run `cp .env .env.local` and update `.env.local`.

#### Client
```
$ npm run build
```

#### Server
Make sure the database is created and running before running the following command

```
$ ./bin/console doctrine:migrations:migrate
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

#### Create superuser
To create a superuser that is able to log in to the admin section simply run

```
$ ./bin/console app:create:superuser
```

and enter an email and a password.