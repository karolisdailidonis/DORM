<?php

namespace DORM\Includes\Auth;

use DORM\Includes\Abstracts\AuthController;
use DORM\Config\Config;

class DirectRequest extends AuthController
{


	protected array $reqArr;

	public function __construct(array $reqArr)
	{
		$this->reqArr = $reqArr;
	}

	public function auth(&$request): bool
	{

		$request = $this->reqArr;

		return true;
	}
}
