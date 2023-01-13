<?php
use DORM\API\Jobs\Job;

class Read extends Job {

	public function mid(){
		try {
			$model = (new $this->dbHandler['class_name']())->deleteData( $this->table );
			$model = $this->dbHandler->execute( $model );
			
		} catch (\PDOException $e) {
			$this->error =  array( 'message' => $e->getMessage(), 'request' => $this->table );

		} catch ( \Throwable $e) {
			$this->error =  array( 'message' => $e->getMessage(), 'request' => $this->table );

		}
	}

}