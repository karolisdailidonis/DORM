<?php
namespace DORM\API;

use DORM\Database\DBHandler;
use DORM\Includes\ModelList;
use DORM\Config\Config;

final class API
{
    protected bool $tokenRequiered;
    protected string $token = '';
    protected string $dbConfig = '';

    public function __construct(bool $tokenRequiered = false, string $dbConfig = 'default')
    {
        $this->tokenRequiered = $tokenRequiered;
        $this->token = Config::$tokens;
        $this->dbConfig = $dbConfig;
        $this->request();
    }

    public function request()
    {
        $request    = json_decode(file_get_contents("php://input"), true);
        $body       = [];
        $errors     = [];

        if ($this->tokenRequiered && !(isset($request['token']) && $request['token'] == $this->token)) {
            $this->response([], ['Permission denied']);
            return false;
        }

        if (isset($request['jobs'] ) && is_array($request['jobs'])) {

            $dbHandler      = new DBHandler($this->dbConfig);
            $modelList      = new ModelList($dbHandler->getConnection());
            $solvedStack    = [];

            foreach ($request['jobs'] as $job) {

                if (isset($job['job'])){
                    $modelFromList = $modelList->findModel($job['from']);

                    if (is_array($modelFromList) && $modelFromList){

                        // Setup/proof
                        $jobname = ucfirst($job['job']);

                        try {
                            if (!@include_once('Jobs/' . $jobname . '.php')) {
                                throw new \Exception('Job does not exist/implemented');
                            }

                            $job = (new \ReflectionClass($jobname))->newInstance($modelFromList, $job, $dbHandler);
                            $job->do();

                            if ($job->getResult() != null) {
                                $body[$modelFromList['table_name']] = $job->getResult();
                            }
                            
                            if ($job->getError() != null){
                                $errors[] = $job->getError();
                            }
                        } catch (\Exception $e) {
                            $errors[] = array('message' =>  $e->getMessage(), 'request' => $job);
                        }

                    } else {
                        $errors[] = array('message' => 'can not found a model in the modellist', 'request' => $job);
                    }
                
                } else {
                    $errors[] = array('message' => 'missing key: job', 'request' => $job);
                }
            }

        } else {
            $errors[] = array('message' => 'no correct request found');
        }

        $this->response($body, $errors);
    }

    public function response($body, $errors)
    {
        header('Content-Type: application/json; charset=UTF-8');

        foreach (Config::$requestHeadersAPI as $value) {
            header($value);
        }

        $response = [];
        $response['db'] = $this->dbConfig;
        $response['body'] = $body;
        $response['errors'] = $errors;

        print_r(json_encode($response));
    }
}
// EOL