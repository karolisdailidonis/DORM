<?php

namespace DORM\API;

use DORM\Database\DBHandler;
use DORM\Includes\ModelList;
use DORM\Includes\Abstracts\AuthController;
use DORM\Includes\ErrorHandler;
use DORM\Config\Config;
use DORM\Includes\DORMError;
use DORM\Includes\DORMInfo;

final class API
{
    protected bool $isAuth = false;
    protected DBHandler $dbHandler;
    protected $request = null;
    public array $response = [];
    protected array $body = [];
    protected bool $JSONwithoutKonst = false;
    protected DORMError $errors;
    protected bool $asJsonContent;


    //TODO: Ref constructor to arr with config values
    public function __construct(AuthController $authController, string $dbConfig, string $tenantDbName = null, bool $asJsonContent = true, bool $noCheckDirect = false)
    {
        ErrorHandler::setup();
        $this->errors = new DORMError();
        try {
            $this->request = json_decode(file_get_contents("php://input"), true);
            $this->isAuth  = $authController->auth($this->request);
            $this->dbHandler = new DBHandler($dbConfig, $tenantDbName);
            $this->asJsonContent = $asJsonContent;
            $this->JSONwithoutKonst = $noCheckDirect;
            $this->request();
        } catch (\Throwable $th) {
            // TODO: sorgt für ein Error 500
            ErrorHandler::apiOutput($th);
            die;
        }
    }

    protected function request()
    {
        if (isset($this->request['apiConfig']['nocheck'])) {
            $this->JSONwithoutKonst = true;
        }

        if (!$this->isAuth) {
            $this->errors->add('[API] Permission denied');
            $this->response();
        }

        if (!isset($this->request['jobs']) && !is_array($this->request['jobs'])) {
            $this->errors->add('[API] No correct request found');
            $this->response();
        }


        $modelList      = new ModelList($this->dbHandler->getConnection());
        $solvedStack    = [];

        foreach ($this->request['jobs'] as $job) {

            if (!isset($job['job'])) {
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
                    $this->errors->add('[API] No Class for this job', $jobname);
                    continue;
                }

                $jobrun = (new \ReflectionClass($jobname))->newInstance($modelFromList, $job, $this->dbHandler);
                $jobrun->do();

                if (isset($job['alias'])) {
                    $this->body[$job['alias']] = $jobrun->getResult();
                } else {
                    $this->body[$modelFromList['table_name']] = $jobrun->getResult();
                }

                $jobError = $jobrun->getError();

                if (count($jobError) > 0) {
                    $this->errors->add('[API] Executed job has an error', $jobError);
                }
            } catch (\Exception $e) {
                $this->errors->add('[API] [' . $jobname . '] ' . $e->getMessage());
            }
        }

        return $this->response($this->asJsonContent);
    }

    // TODO: Refactor, see Response.php
    protected function response(bool $asJsonContent = true)
    {
        // Create response
        $this->response['version'] = DORMInfo::getVersion();
        $this->response['body'] = $this->body;
        $this->response['errors'] = $this->errors->getErrors();

        if (!$asJsonContent) {
            return $this->response;
        }

        header_remove();

        header('Content-Type: application/json; charset=UTF-8');

        foreach (Config::$requestHeadersAPI as $value) {
            header($value);
        }

        http_response_code(200);

        if ($this->JSONwithoutKonst) {
            print_r(json_encode($this->response));
            die;
        }

        print_r(json_encode($this->response, JSON_NUMERIC_CHECK));
        die;
    }
}
// EOL