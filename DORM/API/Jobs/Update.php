<?php
use DORM\API\Jobs\Job;

class Update extends Job{

	public function mid(){
		try {
			$query = $this->model->updateData( $this->job );
			$stmt = $this->dbHandler->execute( $query );
		
			$this->result = array();
			$this->result['query']  = $query;
		
		} catch (\PDOException $e) {
			$this->error = array( 'message' => $e->getMessage(), 'request' => $this->job );
		
		} catch ( \Throwable $e) {
			$this->error = array( 'message' => $e->getMessage(), 'request' => $this->job );
		}

	}
}

