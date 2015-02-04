<?php

class Utilities {
	const PAGE_HOME = "Home";
	const PAGE_RECENT = "News Feed";
	const PAGE_MY_ACTIVITIES = "My Activities";
	const PAGE_COMMUNITY = "Community";
	const PAGE_BROWSE = "Browse";

	
	/**
	 * Get the Facebook app id for this app
	 */
	public static function GetFacebookAppId(){
		$enum = EnumQuery::create()->findOneByName("fb_app_id");
		return $enum->getValue();
	}
	
	
	/**
	 * Get the Facebook app secret for this app
	 */
	public static function GetFacebookAppSecret(){
		$enum = EnumQuery::create()->findOneByName("fb_app_secret");
		return $enum->getValue();
	}
	
	
	/**
	 * Determine the client platform
	 * @param unknown $user_agent
	 * @return string
	 */
	public static function DeterminePlatform($userAgent){
		$cap_user_agent = strtoupper($userAgent);
		if (strpos($cap_user_agent, "WINDOWS")!==false)
			return "Windows";
		elseif (strpos($cap_user_agent, "ANDROID")!==false)
			return "Android";
		elseif (strpos($cap_user_agent, "APPLE-IP")!==false)
			return "iOS";
		else
			return "N/A";
	}
	
	
	/**
	 * Use cURL to save a remote image to a local file
	 * @param unknown $url URL for image retrieval
	 * @param unknown $saveTo Location to save the image
	 */
	public static function SaveImage($url, $saveTo) {
		// use cURL
		$ch = curl_init ($url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
		$data = curl_exec($ch);
		curl_close($ch);
		
		file_put_contents($saveTo, $data);
	}
}

?>