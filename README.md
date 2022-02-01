# DORM
A lightweight PHP ORM with REST-API

Ein PHP ORM Framework mit kaum abbängigkeiten außer den nativen Erweiterungen.

Eine einfache GUI zur Inizialisierung von Modelen anhand der Tabellen in der Datenbank

Minimal PHP Version is 7.4.0

## Setup

```php

Setup.php

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
