<?php

use Facebook\GraphUser;
use Facebook\FacebookRequest;
use Facebook\FacebookSession;

class FacebookUtilities {
	const FACEBOOK_PRIVILEGES = array("email","user_friends","user_groups");
	
	// profile picture sizes
	const PROFILE_PIC_LARGE = 3;
	const PROFILE_PIC_SMALL = 4;
	
	
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
	 * Validate that the user has already registered with our app
	 * Return the User object if true, otherwise return false
	 * @param Facebook session $session
	 * @return User object|boolean
	 */
	public static function CorroborateFacebookLogin(FacebookSession $session){
		// retrieve Facebook ID
		$user_profile = (new FacebookRequest($session, 'GET', '/me'))
			->execute()->getGraphObject(GraphUser::className());
		$fbId = $user_profile->getId();
	
		// verify that the user is in our database
		$user = UserQuery::create()->findOneByFbId($fbId);
		if ($user){
			return $user;
		} else {
			return false;
		}
	}
	
	
	/**
	 * Finalize the login process by saving the Facebook session and the User object into
	 * PHP session
	 * @param FacebookSession $session
	 * @param User $curUserObj
	 */
	public static function FinalizeFacebookLogin(FacebookSession $session, User $curUserObj){
		// create session
		$_SESSION['fb_token'] = $session->getToken();
		$_SESSION['current_user'] = $curUserObj;
		
		// create cookies
		// set expire in 30 days
		$exp = time()+30*24*3600;
		setcookie('fb_token', $session->getToken(), $exp);
	}
	
	
	/**
	 * Log the current user out of the system
	 * Destroy session
	 */
	public static function Logout(){
		// destroy current session
		session_destroy();
		
		// expire cookies
		setcookie("fb_token", "", time()-10);
	}
	
	
	/**
	 * Register the user referred to by the current Facebook session
	 * If successful, return the newly created User object
	 * @param FacebookSession $session
	 * @return User|boolean
	 */
	public static function RegisterFacebookUser(FacebookSession $session){
		// retrieve Facebook profile information
		$user_profile = (new FacebookRequest($session, 'GET', '/me'))
			->execute()->getGraphObject(GraphUser::className());
		$fbId = $user_profile->getId();
		$name = $user_profile->getName();
		$email = $user_profile->getEmail();
	
		// save the user into database
		$user = new User();
		$user->setFbId($fbId);
		$user->setDisplayName($name);
		$user->setEmail($email);
		$user->setDateJoined(time());
		$user->setStatus(User::ACTIVE_STATUS);
		if ($user->save()){
			// save a default activity list
			$actList = new ActivityList();
			$actList->setUser($user);
			$actList->setName(ActivityList::DEFAULT_LIST);
			$actList->save();
				
			return $user;
		}
		else
			return false;
	}
	
	
	/**
	 * Retrieve the user's Facebook profile picture and save it
	 * @param FacebookSession $session
	 * @param User $user
	 */
	public static function GetProfilePicture(FacebookSession $session, User $user){
		// ignore https certificate
		$contextOptions = [
			'http' => [
				'header' => 'Connection: close\r\n'
			],
			'https' => [
				'header' => 'Connection: close\r\n'
			]
		];
		
		$context = stream_context_create(array('https' => array('header'=>'Connection: close\r\n')));
		
		// get large sized profile picture
		$request = new FacebookRequest($session, 'GET', '/me/picture', 
			array (
				'height' => '100',
				'type' => 'normal',
				'width' => '100',
				'redirect' => false
			)
		);
		$response = $request->execute();
		$graphObject = $response->getGraphObject();
		
		// save large profile picture
		Utilities::SaveImage($graphObject->getProperty('url'), 'profile_pic_cache/'.$user->getId().'_large.jpg');
		
		// get small sized profile picture
		$request = new FacebookRequest($session, 'GET', '/me/picture',
			array (
				'height' => '50',
				'type' => 'normal',
				'width' => '50',
				'redirect' => false
			)
		);
		$response = $request->execute();
		$graphObject = $response->getGraphObject();
		
		// save small profile picture
		Utilities::SaveImage($graphObject->getProperty('url'), 'profile_pic_cache/'.$user->getId().'_small.jpg');
	}
	
	
	/**
	 * Create a Facebook app group and include all the participants
	 * @param array $arrActivityUserAssocs
	 * @param bool $forAll If true, will recursively add all the interested friends of each member of $arrActivityUserAssocs
	 */
	public static function CreateGroup(array $arrActivityUserAssocs, $forAll=false){
		// app id and secret to construct the app access token
		$fbAppId = self::GetFacebookAppId();
		$fbAppSecret = self::GetFacebookAppSecret();
		$appSession = FacebookSession::newAppSession($fbAppId, $fbAppSecret);
		
		// execute group creation request
		$request = new FacebookRequest($appSession, "POST", "/{$fbAppId}/groups", array("name" => "Test Group"));
		$response = $request->execute();
		$graphObject = $response->getGraphObject();
		
		// get the group that we have just created
		$groupId = $graphObject->getProperty('id');
		$request = new FacebookRequest($appSession, "GET", "/{$groupId}");
		$response = $request->execute();
		$graphObject = $response->getGraphObject();
		
		// add the users to this empty group
		foreach ($arrActivityUserAssocs as $actAssocObj){
			$userObj = $actAssocObj->getUser();
			
			$request = new FacebookRequest($appSession, 'POST', "/{$groupId}/members", array('member' => $userObj->getFbId()));
			$response = $request->execute();
			$graphObject = $response->getGraphObject();
			var_dump($graphObject);
			die();
		}
	}
}