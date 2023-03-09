<?php
namespace DORM\Includes\Abstracts;

abstract class AuthController {

	public function __construct()
	{
		// Code 
	}

	public abstract function auth(&$request): bool;
}