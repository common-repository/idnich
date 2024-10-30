<?php
	function dnich_sanitize_string($string) {
		if($string != '') {
			$string = strip_tags($string);
			$string = addslashes($string);

			return trim($string);
		}
		else {
			return $string;
		}
	}
	
	function dnich_api_not_null($value) {
		if(is_array($value)) {
			if(sizeof($value) > 0) {
				return true;
			} else {
				return false;
			}
		} else {
			if(($value != '') && (strtolower($value) != 'null') && (strlen(trim($value)) > 0)) {
				return true;
			} else {
				return false;
			}
		}
	}
	
	function dnich_api_rand($min = null, $max = null) {
		if(isset($min) && isset($max)) {
			if($min >= $max) {
				return $min;
			} else {
				return mt_rand($min, $max);
			}
		} 
		else {
			return mt_rand();
		}
	}
	
	function dnich_escape_json_string($value) {
		$escapers = array("\\", "/", "\"", "\n", "\r", "\t", "\x08", "\x0c");
		$replacements = array("\\\\", "\\/", "\\\"", "\\n", "\\r", "\\t", "\\f", "\\b");
		$result = str_replace($escapers, $replacements, $value);
		return $result;
	}