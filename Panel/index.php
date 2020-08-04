<?php
if((int)PHP_VERSION < 7) {
	echo "Проверте версию php. Она должна быть не ниже 7.0!";
	exit;
}

session_start();
use application\core\Router;
require "application/lib/Rb.php";
require "application/lib/TelegramService.php";
require "application/lib/Convertion.php";


spl_autoload_register(function($class) {
	$path = str_replace("\\", "/", $class.".php");
	if(file_exists($path)) {
		require $path;
	}
});

$router = new Router;
$router->run();