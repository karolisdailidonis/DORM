<?php
use DORM\API\Jobs\Job;

class Insert extends Job
{
	public function mid(): void
	{
		try {
			$query = $this->model->create($this->job);

			$this->dbHandler->execute($query);
			$insertResult = array('insertID' => $this->dbHandler->getConnection()->lastInsertId());
			
			// TODO: FIX
			$solvedStack[$this->model->getTableName()] = $insertResult;
		
			$this->result = array();
			$this->result['result'] = $insertResult;
			$this->result['query'] = $query;
		
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
