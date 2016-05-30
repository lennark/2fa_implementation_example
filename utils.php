<?php
	
class Utils {
			
	public function getCountryCode($ip, $default = 'GB') {
		
		$cc = geoip_country_code_by_name($ip);
		
		// We have fallback to UK if unable to determine the country based on IP address
		if (!$cc) $cc = $default;
		
		return $cc;
		
	}
	
	public function getIP() {
		
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		    $ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
		    $ip = $_SERVER['REMOTE_ADDR'];
		}
		
		return $ip;
	}
	
	public function redirect($location) {
		die(header("Location: ".$location));
	}
	
}