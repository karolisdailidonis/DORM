<?php
namespace DORM\Includes\Abstracts;

abstract class JobAfter {

	abstract static public function do($after, &$job, &$result, &$errors): void;
}