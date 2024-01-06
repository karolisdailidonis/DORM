<?php
use DORM\Includes\Abstracts\Job;

class Update extends Job
{
	public function mid(): void
	{
		$query = $this->model->updateData($this->job, $this->dbHandler->getDBType());
		try {
			$this->dbHandler->execute($query);
		
			$this->result = array();
			$this->result['query']  = $query;
		
		} catch (\PDOException $e) {
			$this->error = array('message' => '[JOB] ' . $e->getMessage(), 'request' => ['job' => $this->job, 'query' => $query]);
		
		} catch (\Throwable $e) {
			$this->error = array('message' => '[JOB] ' . $e->getMessage(), 'request' => $this->job);
		}
	}
}

