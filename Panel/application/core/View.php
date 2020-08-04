<?php

namespace application\core;

class View {

	protected $route;
	protected $user;
    public $layout = 'default';

	public function __construct($route, $user) {
		$this->route = $route;
        $this->user = $user;
	}

	public function render($title, $vars = []) {
		extract($vars);
		$path = 'application/views/'.$this->route["controller"]."/".$this->route["action"].'.php';

		if (file_exists($path)) {
			ob_start();
			require $path;
			$content = ob_get_clean();
			require 'application/views/layouts/'.$this->layout.'.php';
		}
	}
	
	public static function redirect($url) {
		header('location: '.$url);
		exit;
	}
	
	public static function errorCode($code) {
		http_response_code($code);

		$path = 'application/views/errors/'.$code.'.php';
		if (file_exists($path)) {
			require $path;
		}
		exit;
	}
}	