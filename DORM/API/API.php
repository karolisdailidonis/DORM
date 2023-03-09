<?php
namespace DORM\API;

use DORM\Database\DBHandler;
use DORM\Includes\ModelList;
use DORM\Includes\Abstracts\AuthController;
use DORM\Config\Config;

final class API
{
    protected bool $isAuth = false;
    protected string $dbConfig;
    protected $request = null;
    protected $body = [];
    protected $errors = [];

    public function __construct(AuthController $authController, string $dbConfig)
    {
        $this->request = json_decode(file_get_contents("php://input"), true);
        $this->isAuth  = $authController->auth($this->request);
        $this->dbConfig = $dbConfig;
        $this->request();
    }

    protected function request()
    {
        if (!$this->isAuth) {
            $this->errors = 'Permission denied';
            $this->response();
            die;
        }

        if (isset($this->request['jobs'] ) && is_array($this->request['jobs'])) {

            $dbHandler      = new DBHandler($this->dbConfig);
            $modelList      = new ModelList($dbHandler->getConnection());
            $solvedStack    = [];

            foreach ($this->request['jobs'] as $job) {

                if (isset($job['job'])){
                    $modelFromList = $modelList->findModel($job['from']);

                    if (is_array($modelFromList) && $modelFromList) {

                        // Setup/proof
                        $jobname = ucfirst($job['job']);

                        try {
                            if (!@include_once('Jobs/' . $jobname . '.php')) {
                                throw new \Exception('Job does not exist/implemented');
                            }

                            $job = (new \ReflectionClass($jobname))->newInstance($modelFromList, $job, $dbHandler);
                            $job->do();

                            if ($job->getResult() != null) {
                                $this->body[$modelFromList['table_name']] = $job->getResult();
                            }
                            
                            if ($job->getError() != null) {
                                $this->errors[] = $job->getError();
                            }

                        } catch (\Exception $e) {
                            $this->errors[] = array('message' =>  $e->getMessage(), 'request' => $job);
                        }

                    } else {
                        $this->errors[] = array('message' => 'can not found a model in the modellist', 'request' => $job);
                    }
                
                } else {
                    $this->errors[] = array('message' => 'missing key: job', 'request' => $job);
                }
            }

        } else {
            $this->errors[] = array('message' => 'no correct request found');
        }

        $this->response();
    }

    protected function response()
    {
        header('Content-Type: application/json; charset=UTF-8');

        foreach (Config::$requestHeadersAPI as $value) {
            header($value);
        }

        $response = [];
        $response['db'] = $this->dbConfig;
        $response['body'] = $this->body;
        $response['errors'] = $this->errors;

        print_r(json_encode($response));
    }
}
// EOL