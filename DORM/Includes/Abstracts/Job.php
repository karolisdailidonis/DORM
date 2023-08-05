<?php
namespace DORM\Includes\Abstracts;

use DORM\Includes\DORMError;

abstract class Job
{
	protected ?array $result = null;
	protected array $error = [];
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

		if (isset($this->job['before']['fromBase64'])) {

			foreach ($this->job['before']['fromBase64'] as $columnname) {

				$this->job['values'][$columnname] = base64_decode($this->job['values'][$columnname]);
			}
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

	final public function getError(): array
	{
		return $this->error;
	}
}