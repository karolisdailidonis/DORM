<?php
use DORM\Includes\Abstracts\Job;

class Replace extends Job
{
	public function mid(): void
	{
		$query = $this->model->replaceData($this->job, $this->dbHandler->getDBType());

		try {

			$this->dbHandler->execute($query);
			
			$this->result = array();
			$this->result['query']  = $query;
		
		} catch (\PDOException $e) {
			$this->error = array( 
				'message' => $e->getMessage() . "( " . $e->getLine() . " | " . $e->getFile() . " )", 
				'request' => $this->job 
			);
		
		} catch (\Throwable $e) {
			$this->error =  array('message' => $e->getMessage(), 'request' => $this->job, 'query' => $query);
		}

	}
}
