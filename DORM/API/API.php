<?php
namespace DORM\API;

use DORM\Database\DBHandler;
use DORM\Includes\ModelList;
use DORM\Includes\Abstracts\AuthController;
use DORM\Includes\ErrorHandler;
use DORM\Config\Config;
use DORM\Includes\DORMError;

final class API
{
    protected bool $isAuth = false;
    protected string $dbConfig;
    protected $request = null;
    protected array $body = [];
    protected DORMError $errors;

    public function __construct(AuthController $authController, string $dbConfig)
    {
        ErrorHandler::setup();
        $this->errors = new DORMError();
        try {
            $this->request = json_decode(file_get_contents("php://input"), true);
            $this->isAuth  = $authController->auth($this->request);
            $this->dbConfig = $dbConfig;
            $this->request();

        } catch (\Throwable $th) {
            // TODO: sorgt für ein Error 500
            ErrorHandler::apiOutput($th);
            die;
        }
    }

    protected function request()
    {
        if (!$this->isAuth) {
            $this->errors->add('[API] Permission denied');
            $this->response();
        }

        if (!isset($this->request['jobs'] ) && !is_array($this->request['jobs'])) {
            $this->errors->add('[API] No correct request found');
            $this->response();
        }

        $dbHandler      = new DBHandler($this->dbConfig);
        $modelList      = new ModelList($dbHandler->getConnection());
        $solvedStack    = [];

        foreach ($this->request['jobs'] as $job) {

            if (!isset($job['job'])){
                $this->errors->add('[API] Missing key: job');
                continue;
            }
            
            $modelFromList = $modelList->findModel($job['from']);
            if (!is_array($modelFromList)) {
                $this->errors->add('[API] Not found model in the modellist');
                continue;
            }
            
            // Setup/proof
            $jobname = ucfirst($job['job']);

            try {
                if (!@include_once('Jobs/' . $jobname . '.php')) {
                    throw new \Exception('Job does not exist/implemented');
                }

                $jobrun = (new \ReflectionClass($jobname))->newInstance($modelFromList, $job, $dbHandler);
                $jobrun->do();

                if ($jobrun->getResult() != null) {
                    if(isset($job['alias'])) {
                        $this->body[$job['alias']] = $jobrun->getResult();
                    } else {
                        $this->body[$modelFromList['table_name']] = $jobrun->getResult();
                    }
                }
                
                if ($jobrun->getError() != null) {
                    $this->errors->add('[API] Executed job has an error', $jobrun->getError());
                }
                    
            } catch (\Exception $e) {
                    $this->errors->add('[API] ['. $jobname . '] ' . $e->getMessage());
            }
        }

        $this->response();
    }

    // TODO: Refactor, see Response.php
    protected function response()
    {
        header('Content-Type: application/json; charset=UTF-8');

        foreach (Config::$requestHeadersAPI as $value) {
            header($value);
        }

        $response = [];
        $response['db'] = $this->dbConfig;
        $response['body'] = $this->body;
        $response['errors'] = $this->errors->getErrors();

        print_r(json_encode($response, JSON_NUMERIC_CHECK));
        die;
    }
}


// EOL