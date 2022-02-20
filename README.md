# DORM
A lightweight PHP ORM framework with REST-API and no dependencies other than the native PHP extensions. In addition, it has a simple GUI for initializing models based on the tables in the database

***
**<font color="red">Caution, the software is still in a very early stage. Is unstable and definitely has security vulnerabilities</font>**
***



Requirements:
- Minimal PHP Version is PHP 8.0
- MariaDB 10.4

Implemented functions:
- PHP model class generator [ Dev ]
- Custom Query Builder [ Dev ]
- API [ Dev ]
- Setup GUI
  - Generate models from selected DB tables [ Dev ]
  - HTTP Post API Request [ Dev ]

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

## API

You can use the DORM API anywhere, such as in your api.php file located in the root directory of the example.com/api.php website.

Just put this two line of Code

```php
use DORM\API\API;

new API();
```

### POST Request

The basic SQL CRUD commands are implemented, but only with simple WHERE and SET clauses. As in the example Shema respectively
- requestJob´s
  - read
    - embed ( left join, if table have references)
  - insert
  - update
  - delete
```json
{
  "schema": "DORM 0.0.2",
  "tables": [
    {
      "requestJob": "read",
      "columns": [
        { "column": "ovc_number" },
        { "column": "lead_child" }
      ],
      "from": "ovc",
      "embed": [
        { "table": "caregiver" }
      ]
    },
    {
      "requestJob": "insert",
      "values": {
        "name": "Bond",
        "surname": "Max",
      },
      "from": "person",
    },
    {
      "requestJob": "update",
      "values": {
        "name": "Bond",
        "surname": "Max",
      },
      "from": "person",
      "where": {
          "column": "person_id",
          "value": 81, 
          "condition": "=" 
      }
    },
    {
      "requestJob": "delete",
      "from": "person",
      "where": {
          "column": "person_id",
          "value": 81, 
          "condition": "=" 
      }
    }
  ]
}
```

### Response
```json
{ 
  "schema": "DORM 0.0.2",
  "tables": {
      "ovc": {
        "rows": [
            {
                "person_id": "22",
                "ovc_number": "23232132132",
                "ovc_status": null,
                "generate_income": "Yes",
                "lead_child": "0",
                "time_sick": "10",
                "vaccinated": "No",
                "seeked_treatment": "No",
                "disability": "Yes",
                "birthcertification": "Yes",
                "who_pays_fee": null,
                "school_id": "39",
                "days_missed": "0",
                "class": "7",
                "caregiver_id": "21",
                "household_id": "1255"
            },
        ]
      },
      "references": {
              "caregiver": {
                  "column": "caregiver_id",
                  "referenced_column": "person_id"
              }
          }
  },
  "errors" : []      
}
```



Inspiration for the custom Query Builder by
https://github.com/devcoder-xyz/php-query-builder
