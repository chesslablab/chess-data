# Chess Data

[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](https://opensource.org/license/mit/)
[![Contributors](https://img.shields.io/github/contributors/chesslablab/chess-data)](https://github.com/chesslablab/chess-data/graphs/contributors)

The simplest yet powerful MySQL chess database to learn and play chess online.

## Installation

Clone the `chesslablab/chess-data` repo into your projects folder. Then `cd` the `chess-data` directory and create an `.env` file:

```txt
cp .env.example .env
```

Run the Docker container in detached mode in the background:

```txt
docker compose up -d
```

Update the `.env` file to your specific needs and create the `chess` database:

```text
docker exec -itu 1000:1000 chess_data_php php cli/db-create.php
```
