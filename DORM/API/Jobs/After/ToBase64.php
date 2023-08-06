<?php
use DORM\Includes\Abstracts\JobAfter;

class ToBase64 extends JobAfter
{
	static public function do($after, &$job, &$result, &$errors): void
	{
		foreach ($after as $columnname) {
			foreach ($result['rows'] as $key => $value) {
				$result['rows'][$key][$columnname] = base64_encode($result['rows'][$key][$columnname]);
			}
		}
	} 
}