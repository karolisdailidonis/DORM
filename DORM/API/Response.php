<?php
namespace DORM\API;

class DORMResponse
{
	// TODO: Refactor in API.php and ErrorHandler.php
	protected string $db;
	protected array $body = [];
	protected array $errors = [];

	public function __construct()
	{

	}


	public function toJSON()
	{
		return '';
	}

}