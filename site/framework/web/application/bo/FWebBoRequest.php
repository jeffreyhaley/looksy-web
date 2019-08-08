<?php
class FWebBoRequest
{

	/**
	 * Takes in and sets the FWebVoRequest based on the expected request values.
	 * 
	 * Starts the session, sets the module, action, data associated with the request, CSRF
	 * and the userName.  These values are a necessity for any request. In most cases they will all
	 * be set.  The edge cases include not being logged in and hitting the home page.
	 * 
	 * @return FWebVoRequest
	 */
	public static function setupRequest()
	{
		// Start the session
		FSessionBoSession::SessionStart();
		self::setupGlobals();
		
		$module 		= isset($_REQUEST['module']) ? $_REQUEST['module'] : NULL;
		$action 		= isset($_REQUEST['action']) ? $_REQUEST['action'] : NULL;
		$data 			= isset($_REQUEST['data']) ? json_decode($_REQUEST['data']) : NULL;
		$csrf 			= isset($_REQUEST['csrf']) ? json_decode($_REQUEST['csrf']) : NULL;
		$authenticated 	= FSecurityBoSecurity::getAuthenticated();
		$userEmail 		= null;
		$userId 		= null;
		
		if ($authenticated)
		{						
			$userEmail 	= FSecurityBoSecurity::getUserEmail();
			$userId 	= FSecurityBoSecurity::getUserId();
		}
			
		$requestVo = FWebVoRequest::Singleton($module, $action, $data, $csrf, $userEmail, $userId, $authenticated);		
		
		return $requestVo;		
	}
	
	/**
	 * Setup the POST and REQUEST variables.
	 * 
	 * There is some weirdness with AngularJS and the POST.  We must manually build the $_POST variable
	 */
	private static function setupGlobals()
	{
		$post = json_decode(file_get_contents("php://input"), true);
	
		if (is_array($post) && empty($_POST))
		{				
			$_REQUEST 	= array_merge($post, $_REQUEST);
			$_POST 		= array_merge($post, $_POST);
		}
	}
}