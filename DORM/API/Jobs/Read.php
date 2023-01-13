<?php
use DORM\Includes\Abstracts\Job;

class Read extends Job {

	public function mid(): void {
		try {
			$modelClass = new $this->modelFromList['class_name']();
			$model      = $modelClass->read( $this->table );
			$stmt       = $this->dbHandler->execute( $model )->fetchAll(\PDO::FETCH_ASSOC);
												
			$tableData                  = array();
			$tableData['rows']          = $stmt;
			$tableData['references']    = $modelClass->getReferences();
			$tableData['query']         = $model;

			// TODO: Outsource to own class
			if( isset($this->table['after']['toBase64']) ) {

				foreach ( $this->table['after']['toBase64'] as $columnname ) {

					foreach ( $tableData['rows'] as $key => $value) {
						$tableData['rows'][$key][$columnname] = base64_encode( $tableData['rows'][$key][$columnname] );
					}
				}
			}

			$this->jobData = $tableData;

		} catch (\PDOException $e) {
			$this->error = array( 'message' => $e->getMessage(), 'request' =>$this->table, 'query' => $model );

		} catch ( \Throwable $e) {
			$this->error = array( 'message' => $e->getMessage(), 'request' => $this->table, 'query' => $model );
		}
	}
}