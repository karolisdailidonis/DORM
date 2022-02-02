# DORM
A lightweight PHP ORM framework with REST-API and no extensions other than the native PHP extensions. In addition, it has a simple GUI for initializing models based on the tables in the database


1. [Install](##Install)
2. [Setup](##Setup)
3. [REST-API](##Rest)

Requirements:
- Minimal PHP Version is 7.4.0

Implemented functions:
- PHP model class generator
- Custom Query Builder
  - Select
- REST API [ open ]
- Setup GUI
  - Generate models from selected DB tables [ open ]
  - Try REST API Request [ open ]

## Install

Simply download the main DORM folder from the src/ and paste it into your project. The project follows PSR-4 code style guide with namespaces and autoload, that means you only need to include the autoload.
```php
include 'DORM/autoload.php';
```
Later the composer option will be added as well.

To call one of the main functions, you must also use ``use`` in one of the three folders.

```php
include 'DORM\API';
include 'DORM\Database';
include 'DORM\Includes';
```
like to call the DBHandler.php class
```php
use DORM\Database\DBHandler;

$connection = new DBHandler();
```

## Setup
The DORM has a simple setup page, which can be found at DORM/Includes/Setup.php


```php
use DORM\Includes\Setup;

new Setup();
```


## DB Table to Model generator
```php
<?php

( new TableToModel( $tableName, $columnsArray ))->writeFile();

?>
```

## REST API

You can use the DORM REST API anywhere, such as in your api.php file located in the root directory of the example.com/api.php website.

Just put this two line of Code

```php
use DORM\API\API;

new API();
```

### POST Request
```json
{
  "tables": [
    {
      "columns": [
        { "column": "name", "as": "Given Name" },
        { "column": "surname" }
      ],
      "from": "person",
      "where": { },
      "join": ""
    },
    {
      "columns": [
        { "column": "name", "as": "Given Name" },
        { "column": "surname" }
      ],
      "from": "location",
      "where": {},
      "join": {}
    }
  ]
}
```

### Response
```json
{
  "tables": {
      "person": {
          "columns": {
                "person_id": 1223,
                "name": "Karolis",
                "surname": "Dailidonis"
            },
            "references": {

            },
            "includes": {

            }
        }
    }      
}
```



Inspiration for the Query Builder by
https://github.com/devcoder-xyz/php-query-builder
