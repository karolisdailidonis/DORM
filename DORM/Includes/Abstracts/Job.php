<?php
namespace DORM\Includes\Abstracts;

use DORM\Includes\DORMError;

abstract class Job
{
	protected array $result = [];
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

		if(is_array($this->job['after'])) {
			$this->after();
		}
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
	
	final protected function after(): void
	{
		foreach ($this->job['after'] as $key => $value) {
			if(!@include_once(__DIR__ . '../../../API/Jobs/After/'.ucfirst($key).'.php')) {
				$this->error[] = "[AFTER] No Class for [" .$key ."]";
				continue;
			}
			('\\' . ucfirst($key))::do($value, $this->job, $this->result, $this->error);
		}
	}

	final public function getResult(): array
	{
		return $this->result;
	}

	final public function getError(): array
	{
		return $this->error;
	}
}