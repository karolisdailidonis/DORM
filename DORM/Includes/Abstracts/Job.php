<?php
namespace DORM\Includes\Abstracts;

abstract class Job
{
	protected ?array $result = null;
	protected ?array $error = null;
	protected $model = null;

	final public function __construct($modelFromList, $job, $dbHandler)
	{
		$this->job = $job;
		$this->dbHandler = $dbHandler;
		$this->model = new $modelFromList['class_name']();
	}

	abstract public function mid(): void;

	final public function do(): void
	{
		$this->before();
		$this->mid();
		$this->after();
	}

	// TODO: Implement like Jobs
	final protected function before(): void
	{
		if (isset($this->job['before']['lastInsertId'])) {
			$before = $this->job['before']['lastInsertId'];
			$this->job['values'][ $before['setColumn']] = $solvedStack[ $before['fromTable']]['insertID'];
		}
	}
	
	// TODO: Implement like Jobs
	final protected function after(): void
	{
		if (isset($this->job['after']['toBase64'])) {

			foreach ($this->job['after']['toBase64'] as $columnname) {

				foreach ($this->result['rows'] as $key => $value) {
					$this->result['rows'][$key][$columnname] = base64_encode($this->result['rows'][$key][$columnname]);
				}
			}
		}
	}

	final public function getResult(): ?array
	{
		return $this->result;
	}

	final public function getError(): ?array
	{
		return $this->error;
	}
}