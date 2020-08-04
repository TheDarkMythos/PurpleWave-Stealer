<?php

namespace application\core;

use application\core\View;
use \R;

abstract class Controller {
	public $route;
	public $view;
	public $acl;
	public $auth;
	public $user;

	public function __construct($route) {
		$this->route = $route;

		if(!$this->CheckDB()) {
			if($this->route["action"] != "install")
				View::redirect("/install");
		}
		else {
			if($this->route["action"] == "install")
				View::redirect("/");
			
			$this->CheckAuth();
		}

		if (!$this->checkAcl()) {
			View::errorCode(404);
		}
		
		$this->view = new View($route, $this->user);
		$this->model = $this->loadModel($route['controller']);
	}

	public function loadModel($name) {
		$path = 'application\\models\\'.ucfirst($name);
		if (class_exists($path)) {
			return new $path($this->user);
		}
	}

	private function CheckAuth() {
		if(isset($_COOKIE["session_id"])) {
			if(isset($_SESSION["user"])) {
				$browserHash = $_SESSION["user"];
			}
			elseif(isset($_COOKIE["auth"])) {
				$browserHash = $_COOKIE["auth"];
			}
			else {
				return false;
			}
			$user = R::findOne("accounts", "id = ?", [$_COOKIE["session_id"]]);

			if($user != null) {
				$easy = md5($user->login."=>".$user->password);
				$hard = md5($user->login."=>".$user->password."=>".$user->fa_code);

				if($easy == $browserHash || $hard == $browserHash) {
					$this->auth = true;
					$this->user = $user;

					$_SESSION["user"] = $browserHash;
					return true;
				}
			}
		}
		
		return false;
	}

	protected function CheckDB() {
		if(file_exists('application/config/db.php')) {
			$config = require "application/config/db.php";
			R::addDatabase('test', 'mysql:host='.$config["host"].';dbname='.$config["dbname"], $config["user"],  $config["password"]);
			R::selectDatabase('test');

			if(R::testConnection()) {
				R::close();
				R::setup('mysql:host='.$config["host"].';dbname='.$config["dbname"], $config["user"],  $config["password"]);
				
				R::selectDatabase('default');
				return true;
			}
			R::close();
		}
		return false;
	}

	public function checkAcl() {
		$this->acl = require 'application/acl/'.$this->route['controller'].'.php';
		if ($this->isAcl('all')) {
			return true;
		}
		elseif ($this->auth and $this->isAcl('authorize')) {
			return true;
		}
		elseif (!$this->auth and $this->isAcl('guest')) {
			return true;
		}
      	elseif (!$this->auth and $this->isAcl('authorize')) {
         	View::redirect("/login");
         	exit;
      	}
		return false;
	}

	public function isAcl($key) {
		return in_array($this->route['action'], $this->acl[$key]);
	}
}