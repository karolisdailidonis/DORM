<?php
use DORM\API\Jobs\Job;

class Insert extends Job {

	public function mid(){
		try {
			// TODO: Outsource to own class
			if ( isset($this->table['before']['lastInsertId'] ) ){
				$before = $this->table['before']['lastInsertId'];
				$this->table['values'][ $before['setColumn']] = $solvedStack[ $before['fromTable']]['insertID'];
			}
		
			$model          = (new $this->modelFromList['class_name']())->create( $this->table );
			$stmt           = $this->dbHandler->execute( $model );
			$lastInsertID   = $this->dbHandler->getConnection()->lastInsertId();
			$result         = array( 'insertID' => $lastInsertID );
		
			$solvedStack[ $this->modelFromList['table_name'] ] = $result;
		
			$tableData              = array();
			$tableData['result']    = $result;
			$tableData['query']     = $model;
		
			$this->jobData = $tableData;
		
		} catch (\PDOException $e) {
			$this->error = array( 
				'message' => $e->getMessage() . "( " . $e->getLine() . " | " . $e->getFile() . " )", 
				'request' => $this->table 
			);
		
		} catch ( \Throwable $e) {
			$this->error =  array( 'message' => $e->getMessage(), 'request' => $this->table, 'query' => $model );
		}

	}
}
