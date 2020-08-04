<?php

namespace application\core;
use \R;
use application\lib\User;
use application\lib\Answer;

abstract class Model {
	public function __construct($user) {
	    $this->user = $user;
	}

	protected function DeleteDir( $path ) {
		if ( file_exists( $path ) AND is_dir( $path ) ) {
			$dir = opendir($path);
			while ( false !== ( $element = readdir( $dir ) ) ) {
				if ( $element != '.' AND $element != '..' )  {
					$tmp = $path . '/' . $element;
					chmod( $tmp, 0777 );
					if ( is_dir( $tmp ) ) {
						$this->DeleteDir( $tmp );
					} else {
						unlink( $tmp );
					}
				}
			}
			closedir($dir);
			if ( file_exists( $path ) ) {
				rmdir( $path );
			}
		}
	}

	protected function ReadDir($path) {
		if ( file_exists( $path ) AND is_dir( $path ) ) {
			$output = [];
			if($dir = opendir($path)) {
				while ( false !== ( $element = readdir( $dir ) ) ) {
					if ( $element != '.' AND $element != '..' )  {
						$tmp = $path . '/' . $element;
						chmod( $tmp, 0777 );
						if ( is_dir( $tmp ) ) {
							$output = array_merge($output, $this->ReadDir( $tmp ));
						} else {
							array_push($output, $tmp);
						}
					}
				}
				closedir($dir);
			}
			return $output;
		}
	}

	protected function filesize_format($filesize)
	{
		$formats = array('Б','КБ','МБ','ГБ','ТБ');
		$format = 0;
		while ($filesize > 1024 && count($formats) != ++$format)
		{
			$filesize = round($filesize / 1024, 2);
		}
		$formats[] = 'ТБ';
		
		return $filesize.$formats[$format];
	}

	protected function SendFile($file_name, $name = "") {
	    if (preg_match("/^[a-zA-Z0-9_\.\/\-]{0,200}\.(xml|txt|pdf|png|gif|jpg|jpeg|exe|doc|xls|ppt|zip|)$/",$file_name) and file_exists($file_name)) {  

	        $extension = strtolower(substr(strrchr($file_name,"."),1));
	        switch ($extension) {
	            case "txt": $ctype="text/plain"; break;
	            case "pdf": $ctype="application/pdf"; break;
	            case "exe": $ctype="application/octet-stream"; break;
	            case "zip": $ctype="application/zip"; break;
	            case "doc": $ctype="application/msword"; break;
	            case "xls": $ctype="application/vnd.ms-excel"; break;
	            case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
	            case "gif": $ctype="image/gif"; break;
	            case "png": $ctype="image/png"; break;
	            case "jpeg": $ctype="image/jpg"; break;
	            case "jpg": $ctype="image/jpg"; break;
	            default: $ctype="application/force-download";
	        }
	        header('Content-Type: '.$ctype.'; charset=utf-8');
	        if($name == "") {
	            header("Content-Disposition: attachment; filename=".$this->random_str(8).".".explode('.', $file_name)[1]);
	        }
	        else {
	            header("Content-Disposition: attachment; filename=".$name);
	        }
	        ob_clean();
	        readfile($file_name);
	    } 
	    else {
	        return "Файл не найден.";
	    }
	}

	protected function random_str( $num = 8 ) {
		return substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, $num);
	}
	
	protected function GetIpData($ip) {
		if($ip == "127.0.0.1") {
			$ip = "localhost";
		}
		$ch = curl_init();
		$optArray = [
		    CURLOPT_URL => "http://www.geoplugin.net/json.gp?ip=".$ip,
		    CURLOPT_RETURNTRANSFER => true
		];
		curl_setopt_array($ch, $optArray);
		$output = curl_exec($ch);
		curl_close($ch);

		$response = json_decode($output);

		return [
			"code" => strtolower($response->geoplugin_countryCode),
			"country" => $response->geoplugin_countryName,
			"region" => $response->geoplugin_region,
		];	
	}
}