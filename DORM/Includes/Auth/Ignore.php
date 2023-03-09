<?php
namespace DORM\Includes\Auth;

use DORM\Includes\Abstracts\AuthController;

class Ignore extends AuthController
{
	public function auth(&$request): bool
	{
		return true;
	}
}
