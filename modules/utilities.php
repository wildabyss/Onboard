<?php

class Utilities {
	const PAGE_HOME = "Home";
	const PAGE_RECENT = "News Feed";
	const PAGE_MY_ACTIVITIES = "My Activities";
	const PAGE_COMMUNITY = "Community";
	const PAGE_BROWSE = "Browse";
	
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