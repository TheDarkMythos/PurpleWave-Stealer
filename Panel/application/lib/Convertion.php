<?php
namespace application\lib;

class Answer {
	private $output = [];

	public function __construct($success = true, $error_text = "", $arr = []) {
		$main = ["success" => $success, 'error_text' => $error_text];
		$main = $main + $arr;

		$this->output = array_merge($this->output, [$main]);
	} 

	public function AddReturnArray($arr) {
		$this->output = array_merge($this->output, [$arr]);
	}

	public function Return() {
		return $this->output;
	}
}