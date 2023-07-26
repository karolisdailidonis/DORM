<?php
use DORM\Includes\Abstracts\Job;

class Delete extends Job
{
	public function mid(): void
	{
		try {
			$query = $this->model->deleteData($this->job, $this->dbHandler->getDBType());
			$this->dbHandler->execute($query);
			
		} catch (\PDOException $e) {
			$this->error =  array('message' => '[JOB] ' . $e->getMessage(), 'request' => $this->job);

		} catch (\Throwable $e) {
			$this->error =  array('message' => '[JOB]: ' . $e->getMessage(), 'request' => $this->job);

		}
	}
}