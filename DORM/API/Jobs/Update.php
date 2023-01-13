<?php
use DORM\API\Jobs\Job;

class Update extends Job{

	public function mid(){
		try {
			$modelClass = new $this->modelFromList['class_name']();
			$model = $modelClass->updateData( $this->table );
			$stmt = $this->dbHandler->execute( $model );
		
			$tableData                  = array();
			$tableData['query']         = $model;
		
			$this->jobData =  $tableData;
		
		} catch (\PDOException $e) {
			$this->error = array( 'message' => $e->getMessage(), 'request' => $this->table );
		
		} catch ( \Throwable $e) {
			$this->error = array( 'message' => $e->getMessage(), 'request' => $this->table );
		}

	}
}

