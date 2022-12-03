# The DORM 0.0.6
A lightweight PHP ORM framework with API and no dependencies other than the native PHP extensions. In addition, it has a simple GUI for initializing models based on the tables in the database

***
**<font color="red">Caution, the software is still in a very early stage. Is unstable and definitely has security vulnerabilities</font>**
***



Run Requirements:
- Minimal PHP Version is PHP 8.0
- MariaDB 10.5.0 or SQL Server 2012 

Implemented functions:
- PHP model class generator [ Dev ]
- Custom Query Builder [ Dev ]
- API [ Dev ]
  - With tokken auth
- Setup GUI
  - Generate models from selected DB tables [ Dev ]
  - HTTP Post API Request [ Dev ]

## Install

Simply download the main DORM folder from the src/ and paste it into your project. The project follows PSR-4 code style guide with namespaces and autoload, that means you only need to include the autoload.

```php
include 'DORM/autoload.php';
```
Later the composer option will be added as well.

Next, rename DORM/Config/Config.sample.php to Config.php and set you database connection data.

For the `db_type` you can use:

| db_type | PDO |
| ------- | --- |
| `mysql` |  php\_pdo\_mysql   |
| `mssql` |  php\_pdo\_sqlsrv\_[php version]\_[ts\|nts]\_[x86\|x64]   |

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
If you want to use token for authentification, give the api class a boolen ```true``` and set in the Config.php file you new token key. 
```php
#api.php
use DORM\API\API;

new API( true );

#DORM/Config/Config.php

...
class Config {

    public static $token = "<you-token-key>";
...

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
  "schema": "DORM 0.0.6",
  "token": "123456",
  "tables": [
    {
      "requestJob": "read",
      "columns": [
        { "column": "<column_name>" },
        { "column": "<column_name>" }
      ],
      "from": "<table_name>",
      "join":[
        { 
          "<table_name>": "<column_name>",
          "<table_name>": "<column_name>"
        }
      ],
      "limit": 1000, // default 1000
      "embed": [
        { "table": "<table_name>" }
      ]
    },
    {
      "requestJob": "insert",
      "values": {
        "<column_name>": "<value>",
        "<column_name>": "<value>",
      },
      "from": "<table_name>",
      "before": {
        "lastInsertId": { 
          "fromTable": "<table_name>", 
          "setColumn": "<column_name>" 
        },
      }
    },
    {
      "requestJob": "update",
      "values": {
        "<column_name>": "<value>",
        "<column_name>": "<value>",
      },
      "from": "<table_name>",
      "where": [
        {
            "column": "<column_name>",
            "value": "<value>", 
            "condition": "=" // all comparison support
        },
        {
            "op": "AND / OR", // default AND
            "column": "<column_name>",
            "val1": "<value>", 
            "val2": "<value>", 
            "condition": "BETWEEN" //only this logical support 
        }
      ]
    },
    {
      "requestJob": "delete",
      "from": "<column_name>",
      "where": {
          "column": "<column_name>",
          "value": "<value>", 
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
