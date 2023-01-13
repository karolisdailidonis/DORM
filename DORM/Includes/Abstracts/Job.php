<?php
namespace DORM\Includes\Abstracts;

abstract class Job {

	protected ?array $jobData = null;
	protected ?array $error = null;

	final public function __construct( $modelFromList, $table, $dbHandler ){
		$this->modelFromList = $modelFromList;
		$this->table = $table;
		$this->dbHandler = $dbHandler;
	}

	abstract public function mid(): void;

	final public function do(): void {
		$this->before();
		$this->mid();
		$this->after();
	}

	// TODO: Implement like Jobs
	final protected function before(){
		return;
	}
	
	// TODO: Implement like Jobs
	final protected function after() {
		return;
	}

	final public function getJobData(): ?array {
		return $this->jobData;
	}

	final public function getError(): ?array {
		return $this->error;
	}
}