<?php

namespace application\lib;

class TelegramService {
	private $token;
	private $error_text;

	public function __construct($token) {
		$this->token = $token;
	}

	public function getError() {
		return $this->error_text;
	}

	public function setWebhook() {
		$result = $this->SendCommand("setWebhook", [
			"url" => "https://".$_SERVER["SERVER_NAME"]."/telegram",
		]);

		if($result["success"] == false)
			$this->error_text = $result["description"];

		return $result["success"];
	}

	public function sendMessage($chat_id, $message, $html = true, $reply_markup = null) {
		if($reply_markup == null) {
			return $this->SendCommand('sendMessage', [
				'chat_id' => $chat_id,
	            'text' => $message,
	            'parse_mode' => (($html == true) ? 'HTML' : ''),
			]);
		}
		else {
			return $this->SendCommand('sendMessage', [
				'chat_id' => $chat_id,
	            'text' => $message,
	            'parse_mode' => (($html == true) ? 'HTML' : ''),
				'reply_markup' => json_encode($reply_markup),
			]);
		}
	}


	

	public function getWebhookInfo() {
		return $this->SendCommand("getWebhookInfo");
	}

	public function Document($caption, $file, $html = true) {
		return $this->SendCommand('sendDocument', [
			'chat_id' => $this->chat_id,
            'caption' => $caption,
            'parse_mode' => (($html == true) ? 'HTML' : ''),
			'document' => new \CURLFile(realpath($file)),
		]);
	}


	

	public function SendCommand($command_name, $command_arr = []) {
		return self::SendData("https://api.telegram.org/bot".$this->token."/".$command_name, $command_arr);
	}

	private static function SendData($url, $fields) {
		$ch = curl_init();
	    $optArray = [
	        CURLOPT_URL => $url,
	        CURLOPT_HTTPHEADER => ["Content-Type: multipart/form-data"],
	        CURLOPT_RETURNTRANSFER => true,
		    CURLOPT_POST => 1,
		    CURLOPT_FOLLOWLOCATION => true,
		    CURLOPT_SSL_VERIFYPEER => false,
		    CURLOPT_POSTFIELDS => $fields,
	    ];
	    curl_setopt_array($ch, $optArray);
	    $output = curl_exec($ch);

	    if($output === false)
		{
		   $output = 'Ошибка curl: ' . curl_error($ch) . "Номер ошибки: " . curl_errno($ch);
		}

	    curl_close($ch);

	    $output = json_decode($output, true);
	    return $output;
	}
}