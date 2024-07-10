# Installation

## Requirements

- PHP >= 8.1

## Setup

Clone the `chesslablab/chess-data` repo into your projects folder. Then `cd` the `chess-data` directory and install the Composer dependencies:

```text
composer install
```

Create an `.env` file:

```text
cp .env.example .env
```

Update the variables in your `.env` file as desired for your specific needs:

```text
DB_DRIVER=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=chess
DB_USERNAME=root
DB_PASSWORD=
```

Finally, create the `chess` database:

```text
php cli/db-create.php
```

## Run the CLI Commands on a Docker Container

Alternatively, you may want to start the Docker containers to run the CLI commands.

```text
docker-compose up -d
```

Then create the `chess` database:

```text
docker exec -itu 1000:1000 chess_data_php_fpm php cli/db-create.php
```
