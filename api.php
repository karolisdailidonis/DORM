<?php
use DORM\API\API;
use DORM\Includes\Auth\SimpleToken;

include_once 'DORM/autoload.php';

new API(new SimpleToken(), 'default');
