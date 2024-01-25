# Installation

## Setup

Clone the `chesslablab/chess-data` repo into your projects folder as it is described in the following example:

```txt
git clone git@github.com:chesslablab/chess-data.git
```

Then `cd` the `chess-data` directory and install the Composer dependencies:

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
