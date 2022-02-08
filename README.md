# DORM
A lightweight PHP ORM framework with REST-API and no extensions other than the native PHP extensions. In addition, it has a simple GUI for initializing models based on the tables in the database


Requirements:
- Minimal PHP Version is 7.4.0

Implemented functions:
- PHP model class generator
- Custom Query Builder
  - Select
  - Insert
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

Next, set in the DORM/Database/config.ini file you database connection data.

To call one of the main functions, you must also use ``use`` in one of the three folders.

```php
use 'DORM\API';
use 'DORM\Database';
use 'DORM\Includes';
```
Like to call the DBHandler.php class
```php
use DORM\Database\DBHandler;

$connection = new DBHandler();
```

Except for the generated classes, these are not in any namespace. There the normal class call is enough and the included autoload does the rest.
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

! Actual possible requests:
- select, from
- requestJob
  - read
  - insert
```json
{
  "schema": "DORM 0.1",
  "tables": [
    {
      "requestJob": "read",
      "columns": [
        { "column": "name", "as": "Given Name" },
        { "column": "surname" }
      ],
      "from": "person",
      "where": { },
      "join": ""
    },
    {
      "requestJob": "insert",
      "values": {
        "name": "Bond",
        "surname": "Max",
      },
      "from": "location",
    }
  ]
}
```

### Response
```json
{ 
  "schema": "DORM 0.1",
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
  },
  "errors" : []      
}
```



Inspiration for the Query Builder by
https://github.com/devcoder-xyz/php-query-builder
