## Chess Data

A chess database, data science and machine learning with [Rubix ML](https://github.com/RubixML/ML).

### Setup

Clone the `chesslablab/chess-data` repo into your projects folder as it is described in the following example:

    $ git clone git@github.com:chesslablab/chess-data.git

Then `cd` the `chess-data` directory and install the Composer dependencies:

    $ composer install

Create an `.env` file:

    $ cp .env.example .env

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

    $ php cli/db-create.php

> If using Docker please read the [bash/start.sh](https://github.com/chesslablab/chess-data/blob/master/bash/start.sh) script for further information.

### Command Line Interface (CLI)

- [Seed the Tables with Data](https://github.com/chesslablab/chess-data/tree/master/cli#seed-the-tables-with-data)
- [Prepare the Data](https://github.com/chesslablab/chess-data/tree/master/cli#prepare-the-data)
- [AI Training](https://github.com/chesslablab/chess-data/tree/master/cli#ai-training)
- [Create JSON Files for the Frontend](https://github.com/chesslablab/chess-data/tree/master/cli#create-json-files-for-the-frontend)

### License

The GNU General Public License.

### Contributions

See the [contributing guidelines](https://github.com/chesslablab/chess-server/blob/master/CONTRIBUTING.md).

Happy learning and coding! Thank you, and keep it up.
