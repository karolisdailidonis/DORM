<?php
use DORM\API\Jobs\Job;

class Insert extends Job {

	public function mid(){
		try {
			$query          = $this->model->create( $this->job );
			$stmt           = $this->dbHandler->execute( $query );
			$lastInsertID   = $this->dbHandler->getConnection()->lastInsertId();
			$insertResult   = array( 'insertID' => $lastInsertID );
		
			$solvedStack[ $this->model->getTableName() ] = $insertResult;
		
			$this->result              = array();
			$this->result['result']    = $insertResult;
			$this->result['query']     = $query;
		
		} catch (\PDOException $e) {
			$this->error = array( 
				'message' => $e->getMessage() . "( " . $e->getLine() . " | " . $e->getFile() . " )", 
				'request' => $this->job 
			);
		
		} catch ( \Throwable $e) {
			$this->error =  array( 'message' => $e->getMessage(), 'request' => $this->job, 'query' => $query );
		}

	}
}
