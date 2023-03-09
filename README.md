# The DORM 0.0.9
A lightweight PHP ORM framework with API and no dependencies other than the native PHP extensions. In addition, it has a simple GUI for initializing models based on the tables in the database

***
**<font color="red">Caution, the software is still in a very early stage. Is unstable and definitely has security vulnerabilities</font>**
***

Requirements:
- Minimal PHP Version is PHP 8.0
- MariaDB 10.5.0 or SQL Server 2012 

Implemented:
- PHP model class generator [ Dev ]
- Custom Query Builder [ Dev ]
- API [ Dev ]
  - With "token" auth
- Setup GUI
  - Generate models from selected DB tables [ Dev ]
  - Test API Requests [ Dev ]

## Client helpers

|  |  |
| ------- | --- |
| npm | [https://www.npmjs.com/package/dorm-handler-js](https://www.npmjs.com/package/dorm-handler-js)|
| dart | [https://github.com/svki0001/DORM-Dart-Client](https://github.com/svki0001/DORM-Dart-Client)|
| c++ | cooming soon|

## Install

Simply download the DORM repo and paste it into your project or Webspace root folder. The project follows PSR-4 code style guide with namespaces and autoload, that means you only need to include the autoload.

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
use DORM\Database\DBHandler;
use DORM\Includes\Setup;

include_once 'DORM/autoload.php';

new Setup();
```

## API Endpoint

You can use the DORM API anywhere, such as in your api.php file located in the root directory of the example.com/api.php website.

Just put this two line of Code

```php
use DORM\API\API;
use DORM\Includes\Auth\Ignore;

include_once 'DORM/autoload.php';

new API(new Ignore(), '<my db config name from Config.php');
```
If you want to use token for authentification, give the api class a boolen ```true``` and set in the Config.php file you new token key. 
```php
#api.php
use DORM\API\API;
use DORM\Includes\Auth\SimpleToken;

include_once 'DORM/autoload.php';

new API(new SimpleToken(), '<my db config name from Config.php');


#DORM/Config/Config.php
...
class Config {

    public static $token = "<you-token-key>";
...

```
### POST Request

The basic SQL CRUD commands are implemented, but only with simple WHERE and SET clauses. As in the example Shema respectively
- requestJob´s
  - [read](https://github.com/karolisdailidonis/DORM/blob/main/doc/Job%20-%20Read.md)
    - embed ( left join, if table have references)
  - [insert](https://github.com/karolisdailidonis/DORM/blob/main/doc/Job%20-%20Insert.md)
  - [update](https://github.com/karolisdailidonis/DORM/blob/main/doc/Job%20-%20Update.md)
  - [delete](https://github.com/karolisdailidonis/DORM/blob/main/doc/Job%20-%20Delete.md)
```json
{
  "schema": "DORM 0.0.6",
  "token": "123456",
  "jobs": [
    {
      "job": "read",
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
      "order": { "column": "<column_name>", "sort": "DESC" },
      "limit": 1000, 
      "embed": [
        { "table": "<table_name>" }
      ]
    },
  ]
}
```

### Response
Here is an example response, more info in the [docu](https://github.com/karolisdailidonis/DORM/blob/main/doc/Responses.md)
```json
{ 
  "body": {
      "ovc": {
        "rows": [
            {
                "person_id": "22",
                "ovc_number": "23232132132",
                "ovc_status": null,
                "generate_income": "Yes",
                "lead_child": "0",
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
