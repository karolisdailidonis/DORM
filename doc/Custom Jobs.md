# Custom Jobs

To implement more "jobs" you just have to create a new file in "DORM/API/Jobs/" which contains the following:

```php
<?php
use DORM\Includes\Abstracts\Job;

class Read extends Job {

	// Declare abstract method
	public function mid(): void {

	// Do things
	// ....

	$this->jobData = $youResult;

	// if something wrong
	$this->error = array( 'message' => "My error message", 'request' =>$this->table, 'query' => $model );

	}
}
```

The following are inherited from the abstract class Job:

```php
	protected ?array $jobData = null;
	protected ?array $error = null;

	final public function __construct( $modelFromList, $table, $dbHandler ){
		$this->modelFromList = $modelFromList;
		$this->table = $table;
		$this->dbHandler = $dbHandler;
	}
```
