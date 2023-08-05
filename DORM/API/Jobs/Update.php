<?php
use DORM\Includes\Abstracts\Job;

class Update extends Job
{
	public function mid(): void
	{
		try {
			$query = $this->model->updateData($this->job, $this->dbHandler->getDBType());
			$this->dbHandler->execute($query);
		
			$this->result = array();
			// $this->result['query']  = $query;
		
		} catch (\PDOException $e) {
			$this->error = array('message' => '[JOB] ' . $e->getMessage(), 'request' => $this->job);
		
		} catch (\Throwable $e) {
			$this->error = array('message' => '[JOB] ' . $e->getMessage(), 'request' => $this->job);
		}
	}
}

