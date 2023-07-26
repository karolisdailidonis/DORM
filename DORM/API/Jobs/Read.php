<?php
use DORM\Includes\Abstracts\Job;

class Read extends Job
{
	public function mid(): void 
	{
		$query = $this->model->read($this->job, $this->dbHandler->getDBType());
		$this->result                   = array();

		try {
			$this->result ['rows']          = $this->dbHandler->execute($query)->fetchAll(\PDO::FETCH_ASSOC);
			$this->result ['references']    = $this->model->getReferences();
			$this->result ['query']         = $query;

		} catch (\PDOException $e) {
			$this->error = array('message' => '[JOB] ' .  $e->getMessage(), 'request' =>$this->job, 'query' => $query);

		} catch ( \Throwable $e) {
			$this->error = array('message' => '[JOB] ' . $e->getMessage(), 'request' => $this->job, 'query' => $query);
		}
	}
}