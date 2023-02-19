<?php
use DORM\API\Jobs\Job;

class Read extends Job
{
	public function mid(): void
	{
		try {
			$query = $this->model->deleteData($this->table, $this->dbHandler->getDBType());
			$this->dbHandler->execute($query);
			
		} catch (\PDOException $e) {
			$this->error =  array('message' => $e->getMessage(), 'request' => $this->job);

		} catch (\Throwable $e) {
			$this->error =  array('message' => $e->getMessage(), 'request' => $this->job);

		}
	}
}