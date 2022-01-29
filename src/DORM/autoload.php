<?php
// namespace DORM;
spl_autoload_register(function ($className) {
	$fileName = dirname(__DIR__, 1 ) . '/' .  $className . '.php';

	if (file_exists($fileName)) {
		require_once $fileName;
	}
});


// spl_autoload_register(function ($className) {
// 	$fileName = dirname(__DIR__, 1) . '/DORM/Controller/' .  $className . '.php';

// 	if (file_exists($fileName)) {
// 		require_once $fileName;
// 	}
// });

?>