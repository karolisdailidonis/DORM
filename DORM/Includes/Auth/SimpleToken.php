<?php
namespace DORM\Includes\Auth;

use DORM\Includes\Abstracts\AuthController;
use DORM\Config\Config;

class SimpleToken extends AuthController
{
	public function auth(&$request): bool
	{
		if(!isset(Config::$token)) {return false;}

        if (isset($request['token']) && $request['token'] == Config::$token) {
			return true;
		}

		return false;
	}
}
