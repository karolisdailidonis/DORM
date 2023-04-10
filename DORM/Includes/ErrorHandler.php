<?php
namespace DORM\Includes;

use DORM\Config\Config;

class ErrorHandler
{
	static public function setup()
	{
		if(isset(Config::$displayErrors) && is_bool(Config::$displayErrors)){
			ini_set('display_errors', Config::$displayErrors);
		} else {
			ini_set('display_errors', false);
		}

		if(isset(Config::$displayErrors) && is_bool(Config::$displayErrors)){
			ini_set('log_errors', Config::$logErrors);
		} else {
			ini_set('log_errors', false);
		}

		ini_set('html_errors', false);

		set_error_handler(function(int $errNo, string $errMsg, string $file, int $line){
			ErrorHandler::log($errMsg, $file);
		});
		
		set_exception_handler(function($exception){
			ErrorHandler::log($exception->getMessage(), $exception->getFile());
		});
	}
	
	static public function log($message, $file = '')
	{
		if (!isset(Config::$paths['logs']) || !is_writable(Config::$paths['logs'])) {
			return false;
		} else {
			error_log("[".date("Y-m-d h:m:s",time())."], ".$file.', '.$message." \n", 3, Config::$paths['logs']);
			return true;
		}
	}
	
	// TODO: Refactor, see Response.php
	static public function apiOutput($error = null)
	{	
		header('Content-Type: application/json; charset=UTF-8');
		
		$response = [];
		$response['body'] = [];
		$response['errors'] = [];
		$response['errors'][] = ["system" => "DORM Fatal Error"];

		if(!ErrorHandler::log($error->getMessage(), $error->getFile())) {
			$response['errors'][] = ["system" => "Log file is not writetable: " . Config::$paths['logs']];
		}

		print_r(json_encode($response));
	}
}