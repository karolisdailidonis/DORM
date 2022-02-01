# DORM
A lightweight PHP ORM with REST-API

Ein PHP ORM Framework mit kaum abbängigkeiten außer den nativen Erweiterungen.

Eine einfache GUI zur Inizialisierung von Modelen anhand der Tabellen in der Datenbank

Minimal PHP Version is 7.4.0

Implemented functions:
- PHP model class generator
- Custom Query Builder
  - Select
- REST API [ open ]
- Setup GUI
  - Generate models from selected DB tables [ open ]
  - Try REST API Request [ open ]


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

### POST
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

### Output
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
