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
	public static function DeterminePlatform($user_agent){
		$cap_user_agent = strtoupper($user_agent);
		if (strpos($cap_user_agent, "WINDOWS")!==false)
			return "Windows";
		elseif (strpos($cap_user_agent, "ANDROID")!==false)
			return "Android";
		elseif (strpos($cap_user_agent, "APPLE-IP")!==false)
			return "iOS";
		else
			return "N/A";
	}
}

?>