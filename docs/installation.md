# Installation

## Setup

Install the Composer packages:

```text
composer install
```

Create an `.env` file:

```text
cp .env.example .env
```

Update the environment variables in your `.env` file if necessary:

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
